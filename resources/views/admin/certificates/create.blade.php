@extends('layouts.admin')

@section('title', 'Issue Certificate')
@section('page_title', 'Issue Certificate')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.certificates.index') }}">Certificates</a></li>
    <li class="breadcrumb-item active" aria-current="page">Issue</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-award text-primary me-2"></i>Issue Student Certificate</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.certificates.store') }}" method="POST">
                    @csrf

                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Select Student <span class="text-danger">*</span></label>
                            <select name="student_id" class="form-select" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name ?? 'Student #' . $student->id }} ({{ $student->admission_number ?? 'No Admission #' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Course Name <span class="text-danger">*</span></label>
                            <input type="text" name="course_name" class="form-control" value="{{ old('course_name', 'Professional Driver Training Program') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Completion Date <span class="text-danger">*</span></label>
                            <input type="date" name="completion_date" class="form-control" value="{{ old('completion_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Grade/Performance <span class="text-danger">*</span></label>
                            <select name="grade" class="form-select" required>
                                <option value="A+" {{ old('grade') == 'A+' ? 'selected' : '' }}>A+ (Distinction)</option>
                                <option value="A" {{ old('grade', 'A') == 'A' ? 'selected' : '' }}>A (Excellent)</option>
                                <option value="B" {{ old('grade') == 'B' ? 'selected' : '' }}>B (Good)</option>
                                <option value="C" {{ old('grade') == 'C' ? 'selected' : '' }}>C (Satisfactory)</option>
                                <option value="Pass" {{ old('grade') == 'Pass' ? 'selected' : '' }}>Pass</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.certificates.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Issue Certificate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
