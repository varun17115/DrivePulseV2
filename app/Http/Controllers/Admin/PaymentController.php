<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::with('student.user')->latest();

            return DataTables::of($payments)
                ->addColumn('student', function ($payment) {
                    return $payment->student->user->name ?? 'N/A';
                })
                ->addColumn('amount', function ($payment) {
                    return '₹' . number_format($payment->amount, 2);
                })
                ->addColumn('status_badge', function ($payment) {
                    $class = match($payment->status) {
                        'completed' => 'bg-success',
                        'pending' => 'bg-warning',
                        'failed' => 'bg-danger',
                        'refunded' => 'bg-info',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge ' . $class . '">' . ucfirst($payment->status) . '</span>';
                })
                ->addColumn('actions', function ($payment) {
                    return '<a href="' . route('admin.payments.print', $payment->id) . '" class="btn btn-sm btn-outline-dark" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        return view('admin.payments.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,upi,bank_transfer,online',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $student = Student::findOrFail($validated['student_id']);

            $payment = Payment::create([
                'student_id' => $validated['student_id'],
                'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-' . mt_rand(100, 999),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'],
                'status' => 'completed',
                'paid_at' => Carbon::now(),
                'notes' => $validated['notes'],
            ]);

            $student->increment('paid_amount', $validated['amount']);
            $student->decrement('due_amount', $validated['amount']);

            // Notify Student
            if ($student->user) {
                NotificationService::send(
                    $student->user,
                    'Payment Received',
                    'Your payment of ₹'.number_format($validated['amount'], 2).' has been received and processed.',
                    'success',
                    route('student.payments.index')
                );
            }
        });

        return back()->with('success', 'Payment recorded successfully!');
    }

    public function print(Payment $payment)
    {
        $payment->load('student.user');
        $pdf = Pdf::loadView('admin.payments.invoice', compact('payment'));
        return $pdf->stream('Invoice-' . $payment->invoice_number . '.pdf');
    }

    public function exportCsv()
    {
        $payments = Payment::with('student.user')->latest()->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivepulse_payments_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Invoice Number', 'Student Name', 'Admission No', 'Amount (₹)', 'Payment Method', 'Transaction ID', 'Status', 'Paid Date'];

        $callback = function () use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $p) {
                fputcsv($file, [
                    $p->invoice_number,
                    $p->student->user->name ?? '',
                    $p->student->admission_number ?? '',
                    number_format($p->amount, 2),
                    ucfirst($p->payment_method),
                    $p->transaction_id ?? '',
                    ucfirst($p->status),
                    $p->paid_at ? $p->paid_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
