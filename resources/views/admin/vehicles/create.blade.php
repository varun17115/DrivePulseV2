@extends('layouts.admin')

@section('title', 'Add New Vehicle')
@section('page_title', 'Add Vehicle')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.vehicles.index') }}">Vehicles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Vehicle</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <form action="{{ route('admin.vehicles.store') }}" method="POST">
            @csrf

            <div class="card border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-car text-primary me-2"></i>Vehicle Specifications</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Registration / Plate # <span class="text-danger">*</span></label>
                            <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}" placeholder="e.g. MH-12-AB-1234" required>
                            @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Make / Brand <span class="text-danger">*</span></label>
                            <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make') }}" placeholder="e.g. Toyota, Hyundai" required>
                            @error('make') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" placeholder="e.g. Corolla, i20" required>
                            @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Manufacturing Year <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', date('Y')) }}" min="1990" max="{{ date('Y') + 1 }}" required>
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Transmission <span class="text-danger">*</span></label>
                            <select name="transmission_type" class="form-select @error('transmission_type') is-invalid @enderror" required>
                                <option value="manual" {{ old('transmission_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                                <option value="automatic" {{ old('transmission_type') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                            </select>
                            @error('transmission_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Fuel Type <span class="text-danger">*</span></label>
                            <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror" required>
                                <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                                <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                            @error('fuel_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold">Initial Odometer (km) <span class="text-danger">*</span></label>
                            <input type="number" name="current_odometer" class="form-control @error('current_odometer') is-invalid @enderror" value="{{ old('current_odometer', 0) }}" min="0" required>
                            @error('current_odometer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 mb-4">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-file-shield text-primary me-2"></i>Compliance & Status</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Insurance Expiry Date</label>
                            <input type="date" name="insurance_expiry" class="form-control @error('insurance_expiry') is-invalid @enderror" value="{{ old('insurance_expiry') }}">
                            @error('insurance_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Fitness Certificate Expiry</label>
                            <input type="date" name="fitness_certificate_expiry" class="form-control @error('fitness_certificate_expiry') is-invalid @enderror" value="{{ old('fitness_certificate_expiry') }}">
                            @error('fitness_certificate_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Pollution / Emission Expiry</label>
                            <input type="date" name="pollution_check_expiry" class="form-control @error('pollution_check_expiry') is-invalid @enderror" value="{{ old('pollution_check_expiry') }}">
                            @error('pollution_check_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Operational Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Available for booking)</option>
                                <option value="in_service" {{ old('status') == 'in_service' ? 'selected' : '' }}>In Service (Under Maintenance)</option>
                                <option value="out_of_order" {{ old('status') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                                <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-5">
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 rounded-pill">
                    <i class="fa-solid fa-save me-1"></i> Save Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
