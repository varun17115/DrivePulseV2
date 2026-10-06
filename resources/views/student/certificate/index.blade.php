@extends('layouts.admin')

@section('title', 'My Driving Certificate')
@section('page_title', 'Course Completion Certificate')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Certificate</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        @if($certificate && $certificate->status === 'issued')
            <div class="card border-0 shadow-sm text-center p-5">
                <div class="mb-3">
                    <i class="fa-solid fa-award text-warning fa-4x"></i>
                </div>
                <h3 class="fw-bold mb-1">Congratulations, {{ $student->user->name }}!</h3>
                <p class="text-muted mb-4">You have successfully completed your driving training program and met all competency standards.</p>

                <div class="p-4 bg-light rounded-4 mb-4 text-start">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Certificate Number</span>
                            <span class="fw-bold font-monospace text-primary fs-5">{{ $certificate->certificate_number }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Course Program</span>
                            <span class="fw-semibold">{{ $certificate->course_name }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Issue Date</span>
                            <span class="fw-semibold">{{ $certificate->issue_date->format('d M, Y') }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Final Evaluation Grade</span>
                            <span class="badge bg-success fs-6">{{ $certificate->grade }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('student.certificate.download', $certificate->id) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-file-pdf me-2"></i> Download Official Certificate (PDF)
                    </a>
                    <a href="{{ route('verify-certificate', $certificate->qr_code_hash) }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fa-solid fa-qrcode me-2"></i> Public Verification Page
                    </a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm text-center p-5">
                <div class="mb-3">
                    <i class="fa-solid fa-graduation-cap text-secondary fa-4x opacity-50"></i>
                </div>
                <h4 class="fw-bold">Certificate Not Yet Available</h4>
                <p class="text-muted mb-4">Complete all practical lessons, pass the driving evaluation, and clear any outstanding dues to receive your official driving certificate.</p>

                <div class="card bg-light border-0 p-4 text-start mb-3">
                    <h6 class="fw-bold mb-3">Requirements Checklist</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa-solid {{ $student->lessons_completed >= $student->total_lessons_booked && $student->total_lessons_booked > 0 ? 'fa-circle-check text-success' : 'fa-circle text-muted' }} me-2"></i>
                        <span>Complete required lesson hours ({{ $student->lessons_completed }}/{{ $student->total_lessons_booked }} completed)</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa-solid {{ $student->due_amount == 0 ? 'fa-circle-check text-success' : 'fa-circle text-muted' }} me-2"></i>
                        <span>Clear all outstanding fee dues (₹{{ number_format($student->due_amount, 2) }} due)</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa-solid {{ $student->course_status === 'completed' ? 'fa-circle-check text-success' : 'fa-circle text-muted' }} me-2"></i>
                        <span>Admin / Trainer final course sign-off (Status: {{ ucfirst($student->course_status) }})</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
