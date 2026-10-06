@extends('layouts.admin')

@section('title', 'Vehicles')
@section('page_title', 'School Vehicles Fleet')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicles</li>
@endsection

@section('content')
<div class="row g-4">
    @forelse($vehicles as $vehicle)
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-subtle text-primary rounded d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-car fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $vehicle->make }} {{ $vehicle->model }}</h6>
                                <div class="small fw-semibold text-monospace mt-1 px-2 py-1 bg-light border rounded d-inline-block">{{ $vehicle->registration_number }}</div>
                            </div>
                        </div>
                        <span class="badge {{ $vehicle->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($vehicle->status) }}
                        </span>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2 text-center bg-light">
                                <div class="small text-muted mb-1"><i class="fa-solid fa-gears me-1"></i> Gear</div>
                                <div class="fw-semibold small">{{ ucfirst($vehicle->transmission_type) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center bg-light">
                                <div class="small text-muted mb-1"><i class="fa-solid fa-gas-pump me-1"></i> Fuel</div>
                                <div class="fw-semibold small">{{ ucfirst($vehicle->fuel_type) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="small mb-3">
                        <div class="d-flex justify-content-between mb-2 pb-1 border-bottom">
                            <span class="text-muted">Total Lessons (with you)</span>
                            <span class="fw-bold text-primary">{{ $vehicle->bookings_count ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Insurance Expiry</span>
                            <span class="fw-semibold {{ $vehicle->insurance_expiry && $vehicle->insurance_expiry->isPast() ? 'text-danger' : '' }}">
                                {{ $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('d M, Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pollution Check Expiry</span>
                            <span class="fw-semibold {{ $vehicle->pollution_check_expiry && $vehicle->pollution_check_expiry->isPast() ? 'text-danger' : '' }}">
                                {{ $vehicle->pollution_check_expiry ? $vehicle->pollution_check_expiry->format('d M, Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm py-5 text-center text-muted">
                <i class="fa-solid fa-car-side fa-3x mb-3 text-secondary"></i>
                <h5>No Vehicles Available</h5>
                <p class="mb-0">There are no vehicles currently assigned or active in the school fleet.</p>
            </div>
        </div>
    @endforelse
</div>

@if($vehicles->hasPages())
    <div class="mt-4">
        {{ $vehicles->links() }}
    </div>
@endif
@endsection
