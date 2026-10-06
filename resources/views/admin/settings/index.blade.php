@extends('layouts.admin')

@section('title', 'System Settings')
@section('page_title', 'System Settings')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-gear text-primary me-2"></i>Global Configuration</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-primary mb-3">General Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">School Name</label>
                            <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $settings['school_name'] ?? 'DrivePulse Driving School') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email'] ?? 'info@drivepulse.com') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone'] ?? '+1 234 567 8900') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="2" required>{{ old('address', $settings['address'] ?? '123 Drive Avenue, Auto City') }}</textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3">Financial Configuration</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Standard Registration Fee (₹)</label>
                            <input type="number" step="0.01" name="registration_fee" class="form-control" value="{{ old('registration_fee', $settings['registration_fee'] ?? '50.00') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Standard Hourly Rate (₹)</label>
                            <input type="number" step="0.01" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $settings['hourly_rate'] ?? '40.00') }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3">Operational Hours</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Start Time</label>
                            <input type="time" name="working_hours_start" class="form-control" value="{{ old('working_hours_start', $settings['working_hours_start'] ?? '08:00') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">End Time</label>
                            <input type="time" name="working_hours_end" class="form-control" value="{{ old('working_hours_end', $settings['working_hours_end'] ?? '18:00') }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
