<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $certificate = Certificate::where('student_id', $student->id)->first();

        return view('student.certificate.index', compact('student', 'certificate'));
    }

    public function download(Certificate $certificate)
    {
        $student = Auth::user()->student;
        if (!$student || $certificate->student_id !== $student->id) abort(403);

        $pdf = Pdf::loadView('student.certificate.certificate_pdf', compact('certificate', 'student'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Driving_Certificate_'.$certificate->certificate_number.'.pdf');
    }
}
