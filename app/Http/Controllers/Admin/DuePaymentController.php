<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmailLog;
use App\Models\Payment;
use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DuePaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dueStudents = Student::with('user')
                ->where('due_amount', '>', 0)
                ->latest();

            return DataTables::of($dueStudents)
                ->addColumn('student_name', function ($s) {
                    $name = $s->user ? e($s->user->name) : 'Student';
                    $email = $s->user ? e($s->user->email) : '-';
                    $phone = $s->emergency_contact ? e($s->emergency_contact) : '-';
                    return '
                        <div class="fw-bold text-dark">' . $name . '</div>
                        <div class="small text-muted font-monospace">' . $s->admission_number . ' | ' . $email . '</div>
                        <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i>' . $phone . '</div>
                    ';
                })
                ->addColumn('total_fee', function ($s) {
                    return '<span class="font-monospace fw-bold">₹' . number_format($s->total_amount, 2) . '</span>';
                })
                ->addColumn('paid_fee', function ($s) {
                    return '<span class="font-monospace text-success fw-bold">₹' . number_format($s->paid_amount, 2) . '</span>';
                })
                ->addColumn('pending_due', function ($s) {
                    return '<span class="font-monospace text-danger fs-6 fw-bold">₹' . number_format($s->due_amount, 2) . '</span>';
                })
                ->addColumn('course_badge', function ($s) {
                    return '<span class="badge bg-light text-dark border">' . e($s->license_type) . '</span>';
                })
                ->addColumn('actions', function ($s) {
                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 btn-pay-installment" 
                                data-id="' . $s->id . '" 
                                data-name="' . e($s->user->name ?? 'Student') . '"
                                data-adm="' . $s->admission_number . '"
                                data-due="' . $s->due_amount . '"
                                data-paid="' . $s->paid_amount . '"
                                data-total="' . $s->total_amount . '">
                                <i class="fa-solid fa-money-bill-wave me-1"></i> Pay Installment
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 btn-send-reminder"
                                data-id="' . $s->id . '"
                                data-name="' . e($s->user->name ?? 'Student') . '"
                                data-due="' . $s->due_amount . '">
                                <i class="fa-solid fa-bell me-1"></i> Remind
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['student_name', 'total_fee', 'paid_fee', 'pending_due', 'course_badge', 'actions'])
                ->make(true);
        }

        $totalDueAmount = Student::sum('due_amount');
        $dueStudentsCount = Student::where('due_amount', '>', 0)->count();
        $totalPaidAmount = Student::sum('paid_amount');
        $highestDue = Student::max('due_amount') ?? 0;

        return view('admin.payments.due', compact('totalDueAmount', 'dueStudentsCount', 'totalPaidAmount', 'highestDue'));
    }

    public function recordInstallment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,card,upi,bank_transfer,online',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $student = Student::with('user')->findOrFail($validated['student_id']);

        if ($validated['amount'] > $student->due_amount) {
            return response()->json([
                'success' => false,
                'message' => "Installment amount (₹" . number_format($validated['amount'], 2) . ") cannot exceed current pending due balance (₹" . number_format($student->due_amount, 2) . ")."
            ], 422);
        }

        $invoiceNo = 'INV-' . strtoupper(Str::random(8));

        // Create Payment record
        $payment = Payment::create([
            'student_id' => $student->id,
            'invoice_number' => $invoiceNo,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => $validated['notes'] ?? 'Installment payment toward pending course fees',
        ]);

        // Update student balances
        $student->paid_amount += $validated['amount'];
        $student->due_amount = max(0, $student->due_amount - $validated['amount']);
        $student->save();

        ActivityLog::log(
            'INSTALLMENT_RECORDED',
            "Recorded installment of ₹" . number_format($validated['amount'], 2) . " for student {$student->user->name} ({$student->admission_number}). Invoice: {$invoiceNo}",
            'Payments'
        );

        if ($student->user) {
            NotificationService::send(
                $student->user,
                'Payment Received: ₹' . number_format($validated['amount'], 2),
                "Thank you! We received your installment of ₹" . number_format($validated['amount'], 2) . ". Remaining balance: ₹" . number_format($student->due_amount, 2),
                'success',
                route('student.payments.print', $payment->id)
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Installment payment recorded successfully!',
            'invoice_url' => route('admin.payments.print', $payment->id),
            'remaining_due' => number_format($student->due_amount, 2),
        ]);
    }

    public function sendReminder(Request $request, Student $student)
    {
        if ($student->due_amount <= 0) {
            return response()->json(['success' => false, 'message' => 'This student has no outstanding due balance.']);
        }

        $studentName = $student->user->name ?? 'Student';
        $email = $student->user->email ?? null;

        if ($student->user) {
            NotificationService::send(
                $student->user,
                'Fee Payment Reminder: ₹' . number_format($student->due_amount, 2) . ' Pending',
                "Dear {$studentName}, you have an outstanding fee balance of ₹" . number_format($student->due_amount, 2) . ". Kindly complete payment at your earliest convenience.",
                'warning'
            );
        }

        if ($email) {
            EmailLog::create([
                'recipient_email' => $email,
                'subject' => 'Outstanding Fee Reminder: DrivePulse Driving School',
                'body' => "Dear {$studentName},<br><br>This is a gentle reminder regarding your pending course fee balance of <strong>₹" . number_format($student->due_amount, 2) . "</strong>.<br>Please contact the administration desk to clear your dues.<br><br>Thank you,<br>DrivePulse Administration",
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        ActivityLog::log('DUE_REMINDER_SENT', "Sent due payment reminder to {$studentName} for ₹" . number_format($student->due_amount, 2), 'Payments');

        return response()->json([
            'success' => true,
            'message' => "Fee payment reminder sent successfully to {$studentName}."
        ]);
    }

    public function bulkRemind()
    {
        $dueStudents = Student::with('user')->where('due_amount', '>', 0)->get();
        $count = 0;

        foreach ($dueStudents as $student) {
            if ($student->user) {
                NotificationService::send(
                    $student->user,
                    'Pending Balance Reminder: ₹' . number_format($student->due_amount, 2),
                    "Dear {$student->user->name}, please note you have an outstanding tuition balance of ₹" . number_format($student->due_amount, 2) . ".",
                    'warning'
                );

                if ($student->user->email) {
                    EmailLog::create([
                        'recipient_email' => $student->user->email,
                        'subject' => 'Tuition Balance Due Reminder',
                        'body' => "Dear {$student->user->name},<br><br>You currently have an outstanding balance of <strong>₹" . number_format($student->due_amount, 2) . "</strong>.<br>Kindly visit the academy office or pay online.<br><br>DrivePulse Accounts Team",
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                }
                $count++;
            }
        }

        ActivityLog::log('BULK_DUE_REMINDERS', "Dispatched bulk due payment reminders to {$count} student(s)", 'Payments');

        return redirect()->route('admin.due-payments.index')
            ->with('success', "Dispatched fee due reminders to {$count} student(s) successfully!");
    }

    public function exportCsv()
    {
        $students = Student::with('user')->where('due_amount', '>', 0)->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivepulse_due_payments_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Admission No', 'Student Name', 'Email', 'Phone', 'Course Package', 'Total Fee (₹)', 'Paid Amount (₹)', 'Due Amount (₹)', 'Enrollment Date'];

        $callback = function () use ($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $s) {
                fputcsv($file, [
                    $s->admission_number,
                    $s->user->name ?? '',
                    $s->user->email ?? '',
                    $s->emergency_contact ?? '',
                    $s->license_type,
                    number_format($s->total_amount, 2),
                    number_format($s->paid_amount, 2),
                    number_format($s->due_amount, 2),
                    $s->enrollment_date,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
