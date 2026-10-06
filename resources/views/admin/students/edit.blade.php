@extends('layouts.admin')

@section('title', 'Edit Student - ' . $student->user->name)
@section('page_title', 'Edit Student')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- User / Account Information -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-id-card text-primary me-2"></i>Account & Identity</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->user->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Admission Number <span class="text-danger">*</span></label>
                            <input type="text" name="admission_number" class="form-control @error('admission_number') is-invalid @enderror" value="{{ old('admission_number', $student->admission_number) }}" required>
                            @error('admission_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">New Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 characters">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type new password">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile & Personal Details -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-user text-primary me-2"></i>Personal Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}">
                            @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">License Type <span class="text-danger">*</span></label>
                            <select name="license_type" class="form-select @error('license_type') is-invalid @enderror" required>
                                <option value="Class C - Manual" {{ old('license_type', $student->license_type) == 'Class C - Manual' ? 'selected' : '' }}>Class C - Manual</option>
                                <option value="Class C - Automatic" {{ old('license_type', $student->license_type) == 'Class C - Automatic' ? 'selected' : '' }}>Class C - Automatic</option>
                                <option value="Class A - Motorcycle" {{ old('license_type', $student->license_type) == 'Class A - Motorcycle' ? 'selected' : '' }}>Class A - Motorcycle</option>
                                <option value="Commercial Heavy Vehicle" {{ old('license_type', $student->license_type) == 'Commercial Heavy Vehicle' ? 'selected' : '' }}>Commercial Heavy Vehicle</option>
                            </select>
                            @error('license_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Residential Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $student->address) }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Emergency Contact Person</label>
                            <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}">
                            @error('emergency_contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Emergency Contact Number</label>
                            <input type="text" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact', $student->emergency_contact) }}">
                            @error('emergency_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course & Financials -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>Course & Fee Setup</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Enrollment Date <span class="text-danger">*</span></label>
                            <input type="date" name="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date', $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '') }}" required>
                            @error('enrollment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Course Status <span class="text-danger">*</span></label>
                            <select name="course_status" class="form-select @error('course_status') is-invalid @enderror" required>
                                <option value="enrolled" {{ old('course_status', $student->course_status) == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                <option value="in_progress" {{ old('course_status', $student->course_status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('course_status', $student->course_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="dropped" {{ old('course_status', $student->course_status) == 'dropped' ? 'selected' : '' }}>Dropped</option>
                            </select>
                            @error('course_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Total Course Fee (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount', $student->total_amount) }}" required>
                            @error('total_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Total Paid Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', $student->paid_amount) }}" required>
                            @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-5">
                <a href="{{ route('admin.students.index') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 rounded-pill">
                    <i class="fa-solid fa-save me-1"></i> Update Student
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
