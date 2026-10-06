<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $certificates = Certificate::with('student.user')->latest();

            return DataTables::of($certificates)
                ->addColumn('student', function ($cert) {
                    return $cert->student->user->name ?? 'N/A';
                })
                ->addColumn('status_badge', function ($cert) {
                    return $cert->status === 'issued'
                        ? '<span class="badge bg-success">Issued</span>'
                        : '<span class="badge bg-danger">Revoked</span>';
                })
                ->addColumn('actions', function ($cert) {
                    $printUrl = route('admin.certificates.print', $cert->id);
                    $revokeBtn = $cert->status === 'issued'
                        ? '<button type="button" class="btn btn-sm btn-outline-danger btn-revoke" data-url="' . route('admin.certificates.revoke', $cert->id) . '">Revoke</button>'
                        : '';

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $printUrl . '" class="btn btn-sm btn-outline-primary" target="_blank" title="Print Certificate"><i class="fa-solid fa-print"></i></a>
                            ' . $revokeBtn . '
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        return view('admin.certificates.index');
    }

    public function create()
    {
        // Get students who have completed their required tasks but do not have an active certificate yet.
        $students = Student::with('user')
            ->whereDoesntHave('certificates', function ($q) {
                $q->where('status', 'issued');
            })
            ->get();

        return view('admin.certificates.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_name' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'completion_date' => 'required|date|before_or_equal:issue_date',
            'grade' => 'required|in:A+,A,B,C,Pass',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        $certificate = Certificate::create([
            'student_id' => $validated['student_id'],
            'certificate_number' => 'CERT-' . strtoupper(Str::random(10)),
            'course_name' => $validated['course_name'],
            'issue_date' => $validated['issue_date'],
            'completion_date' => $validated['completion_date'],
            'grade' => $validated['grade'],
            'qr_code_hash' => hash('sha256', Str::random(40) . time()),
            'status' => 'issued',
        ]);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificate issued successfully!');
    }

    public function print(Certificate $certificate)
    {
        $certificate->load('student.user');

        // Define QR Code URL to verify the certificate
        // For simplicity now, we hash it. The actual verify route will be public
        $verifyUrl = route('verify-certificate', $certificate->qr_code_hash);

        $pdf = Pdf::loadView('admin.certificates.template', compact('certificate', 'verifyUrl'))
                   ->setPaper('a4', 'landscape');

        return $pdf->stream('Certificate-' . $certificate->certificate_number . '.pdf');
    }

    public function revoke(Certificate $certificate)
    {
        $certificate->update(['status' => 'revoked']);
        return response()->json(['success' => true, 'message' => 'Certificate revoked successfully.']);
    }
}
