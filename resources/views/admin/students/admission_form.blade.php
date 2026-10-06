@extends('layouts.admin')

@section('title', 'Admission Form - ' . $student->admission_number)
@section('page_title', 'Student Admission Form & Training Agreement')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.show', $student->id) }}">{{ $student->user->name ?? 'Student' }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Admission Form</li>
@endsection

@push('styles')
<style>
    .admission-document {
        background: #ffffff;
        border: 2px solid #1e293b;
        border-radius: 12px;
        padding: 40px;
        max-width: 860px;
        margin: 0 auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        color: #1e293b;
        font-family: system-ui, -apple-system, sans-serif;
    }
    .admission-header {
        border-bottom: 3px double #1e293b;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }
    .watermark-text {
        letter-spacing: 2px;
    }
    .field-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 2px;
    }
    .field-value {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
    }
    .signature-box {
        border-top: 1.5px solid #334155;
        padding-top: 8px;
        margin-top: 60px;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
    }
    @media print {
        sidebar, header, .btn-print-toolbar, footer { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; }
        .admission-document { border: none !important; box-shadow: none !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
    }
</style>
@endpush

@section('content')
<!-- Action Toolbar -->
<div class="d-flex justify-content-between align-items-center mb-4 max-w-3xl mx-auto btn-print-toolbar flex-wrap gap-2">
    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-2"></i> Back to Student Profile
    </a>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-bold" id="btnEmailAdmission">
            <i class="fa-solid fa-envelope me-2"></i> Email to Student
        </button>
        <button type="button" onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-print me-2"></i> Print Official Form
        </button>
    </div>
</div>

<!-- Admission Document (A4 Printable Format) -->
<div class="admission-document">
    <!-- Header -->
    <div class="admission-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="p-3 bg-primary text-white rounded-3 fs-2 fw-bold">
                <i class="fa-solid fa-steering-wheel"></i> DP
            </div>
            <div>
                <h3 class="fw-extrabold text-dark mb-0 tracking-tight">DRIVEPULSE ACADEMY</h3>
                <p class="text-muted small mb-0 font-monospace">Official Driver Training & Licensing Contract</p>
            </div>
        </div>
        <div class="text-end">
            <span class="badge bg-dark px-3 py-2 fs-6 font-monospace mb-1">{{ $student->admission_number }}</span>
            <div class="small text-muted">Date: {{ $student->enrollment_date ? $student->enrollment_date->format('M d, Y') : date('M d, Y') }}</div>
        </div>
    </div>

    <!-- Student Information Grid -->
    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user me-2 text-primary"></i>1. Student Personal Details</h5>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="field-label">Full Name</div>
            <div class="field-value">{{ $student->user->name ?? 'N/A' }}</div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Email Address</div>
            <div class="field-value">{{ $student->user->email ?? 'N/A' }}</div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Primary Contact</div>
            <div class="field-value">{{ $student->emergency_contact ?? ($student->user->phone ?? 'N/A') }}</div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Date of Birth</div>
            <div class="field-value">{{ $student->dob ? $student->dob->format('d M Y') : 'N/A' }}</div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Gender</div>
            <div class="field-value">{{ ucfirst($student->gender ?? 'Not Specified') }}</div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Emergency Contact Person</div>
            <div class="field-value">{{ $student->emergency_contact_name ?? 'N/A' }}</div>
        </div>
        <div class="col-12">
            <div class="field-label">Residential Address</div>
            <div class="field-value">{{ $student->address ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Course & Training Details -->
    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-id-card me-2 text-primary"></i>2. Course Enrollment Details</h5>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="field-label">Enrolled License Category</div>
            <div class="field-value text-primary fw-bold">{{ $student->license_type }}</div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Enrollment Status</div>
            <div class="field-value"><span class="badge bg-success-subtle text-success border border-success-subtle text-uppercase">{{ str_replace('_', ' ', $student->course_status) }}</span></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="field-label">Total Lessons Package</div>
            <div class="field-value">{{ $student->total_lessons_booked > 0 ? $student->total_lessons_booked : 20 }} Practical & Theory Hours</div>
        </div>
    </div>

    <!-- Financial Breakdown -->
    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-receipt me-2 text-primary"></i>3. Fee Structure & Payment Agreement</h5>
    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-4 text-center">
            <div class="field-label">Total Course Fee</div>
            <div class="fs-5 fw-bold text-dark font-monospace">₹{{ number_format($student->total_amount, 2) }}</div>
        </div>
        <div class="col-4 text-center border-start border-end">
            <div class="field-label">Initial Paid Amount</div>
            <div class="fs-5 fw-bold text-success font-monospace">₹{{ number_format($student->paid_amount, 2) }}</div>
        </div>
        <div class="col-4 text-center">
            <div class="field-label">Remaining Balance Due</div>
            <div class="fs-5 fw-bold text-danger font-monospace">₹{{ number_format($student->due_amount, 2) }}</div>
        </div>
    </div>

    <!-- Terms and Conditions -->
    <h5 class="fw-bold text-dark border-bottom pb-2 mb-2"><i class="fa-solid fa-scale-balanced me-2 text-primary"></i>4. Training Terms & Agreement</h5>
    <ol class="small text-muted ps-3 mb-5">
        <li>The student agrees to adhere to all road safety regulations, instructions given by the assigned certified driving instructor, and vehicle safety protocols at all times.</li>
        <li>Lesson cancellation must be notified at least 24 hours prior to the scheduled lesson start time to prevent forfeiting the session.</li>
        <li>Outstanding fee balances must be cleared prior to booking official road licensing tests or obtaining graduation certificates.</li>
        <li>The driving school maintains comprehensive motor training insurance covering practical driving sessions under certified supervision.</li>
    </ol>

    <!-- Signatures -->
    <div class="row g-4 pt-4">
        <div class="col-6">
            <div class="signature-box">
                Student Signature & Date
            </div>
        </div>
        <div class="col-6">
            <div class="signature-box">
                Authorized Driving School Administrator
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnEmailAdmission').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Emailing...');

        $.ajax({
            url: "{{ route('admin.students.email-admission', $student->id) }}",
            type: "POST",
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                Swal.fire('Email Dispatched', res.message, 'success');
            },
            error: function() {
                Swal.fire('Error', 'Failed to dispatch email confirmation.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-envelope me-2"></i> Email to Student');
            }
        });
    });
});
</script>
@endpush
