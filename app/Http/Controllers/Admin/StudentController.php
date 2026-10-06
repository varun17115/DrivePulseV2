<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $students = Student::with('user')->select('students.*');

            return DataTables::of($students)
                ->addColumn('name', function ($student) {
                    return $student->user ? $student->user->name : 'N/A';
                })
                ->addColumn('email', function ($student) {
                    return $student->user ? $student->user->email : 'N/A';
                })
                ->addColumn('phone', function ($student) {
                    return $student->user ? ($student->user->phone ?? 'N/A') : 'N/A';
                })
                ->addColumn('course_status_badge', function ($student) {
                    $badges = [
                        'enrolled' => 'bg-info',
                        'in_progress' => 'bg-primary',
                        'completed' => 'bg-success',
                        'dropped' => 'bg-danger',
                    ];
                    $badgeClass = $badges[$student->course_status] ?? 'bg-secondary';
                    $statusLabel = ucwords(str_replace('_', ' ', $student->course_status));
                    return '<span class="badge ' . $badgeClass . '">' . $statusLabel . '</span>';
                })
                ->addColumn('financials', function ($student) {
                    return '<div><span class="text-success fw-semibold">Paid: ₹' . number_format($student->paid_amount, 2) . '</span><br><span class="text-danger">Due: ₹' . number_format($student->due_amount, 2) . '</span></div>';
                })
                ->addColumn('actions', function ($student) {
                    $showUrl = route('admin.students.show', $student->id);
                    $editUrl = route('admin.students.edit', $student->id);
                    $deleteUrl = route('admin.students.destroy', $student->id);

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $showUrl . '" class="btn btn-sm btn-outline-info" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-url="' . $deleteUrl . '" data-name="' . ($student->user ? e($student->user->name) : 'Student') . '" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['course_status_badge', 'financials', 'actions'])
                ->make(true);
        }

        return view('admin.students.index');
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            $user->assignRole('student');

            $dueAmount = max(0, $validated['total_amount'] - $validated['paid_amount']);

            Student::create([
                'user_id' => $user->id,
                'admission_number' => $validated['admission_number'],
                'dob' => $validated['dob'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'license_type' => $validated['license_type'],
                'enrollment_date' => $validated['enrollment_date'],
                'course_status' => $validated['course_status'],
                'total_amount' => $validated['total_amount'],
                'paid_amount' => $validated['paid_amount'],
                'due_amount' => $dueAmount,
            ]);

            ActivityLog::log('STUDENT_ENROLLED', "Enrolled student {$user->name} ({$validated['admission_number']}) in {$validated['license_type']}", 'Students');

            DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Student enrolled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to enroll student: ' . $e->getMessage());
        }
    }

    public function show(Student $student)
    {
        $student->load(['user', 'bookings.trainer.user', 'bookings.vehicle', 'payments', 'progress', 'certificate']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        return view('admin.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = $student->user;
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            $dueAmount = max(0, $validated['total_amount'] - $validated['paid_amount']);

            $student->update([
                'admission_number' => $validated['admission_number'],
                'dob' => $validated['dob'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'license_type' => $validated['license_type'],
                'enrollment_date' => $validated['enrollment_date'],
                'course_status' => $validated['course_status'],
                'total_amount' => $validated['total_amount'],
                'paid_amount' => $validated['paid_amount'],
                'due_amount' => $dueAmount,
            ]);

            ActivityLog::log('STUDENT_UPDATED', "Updated student profile for {$user->name} ({$student->admission_number})", 'Students');

            DB::commit();

            return redirect()->route('admin.students.index')->with('success', 'Student details updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student)
    {
        try {
            $user = $student->user;
            $name = $user ? $user->name : $student->admission_number;

            ActivityLog::log('STUDENT_DELETED', "Deleted student record for {$name}", 'Students');

            if ($user) {
                $user->delete();
            } else {
                $student->delete();
            }

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Student deleted successfully!']);
            }

            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete student: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }

    public function admissionForm(Student $student)
    {
        $student->load(['user', 'payments']);
        return view('admin.students.admission_form', compact('student'));
    }

    public function emailAdmissionForm(Student $student)
    {
        $student->load('user');
        if (!$student->user || !$student->user->email) {
            return response()->json(['success' => false, 'message' => 'Student email not found.'], 404);
        }

        $email = $student->user->email;
        $name = $student->user->name;

        \App\Models\EmailLog::create([
            'recipient_email' => $email,
            'subject' => 'Official Admission Form & Contract: ' . $student->admission_number,
            'body' => "Dear {$name},<br><br>Welcome to DrivePulse Driving Academy! Your official admission application and student contract (Admission No: <strong>{$student->admission_number}</strong>) has been processed.<br>You can view and print your admission form at any time from your student portal.<br><br>DrivePulse Administration",
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        \App\Services\NotificationService::send(
            $student->user,
            'Admission Form Available',
            "Your official DrivePulse admission contract ({$student->admission_number}) is ready to download.",
            'success'
        );

        ActivityLog::log('ADMISSION_FORM_EMAILED', "Emailed admission contract to {$name} ({$email})", 'Students');

        return response()->json(['success' => true, 'message' => "Admission form and contract confirmation emailed to {$email}!"]);
    }

    public function exportCsv()
    {
        $students = Student::with('user')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivepulse_students_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Admission No', 'Student Name', 'Email', 'Phone', 'DOB', 'Gender', 'Course Status', 'License Type', 'Total Fee (₹)', 'Paid (₹)', 'Due (₹)', 'Enrollment Date'];

        $callback = function () use ($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $s) {
                fputcsv($file, [
                    $s->admission_number,
                    $s->user->name ?? '',
                    $s->user->email ?? '',
                    $s->emergency_contact ?? ($s->user->phone ?? ''),
                    $s->dob ? $s->dob->format('Y-m-d') : '',
                    ucfirst($s->gender ?? ''),
                    $s->course_status,
                    $s->license_type,
                    number_format($s->total_amount, 2),
                    number_format($s->paid_amount, 2),
                    number_format($s->due_amount, 2),
                    $s->enrollment_date ? $s->enrollment_date->format('Y-m-d') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
