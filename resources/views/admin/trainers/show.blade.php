@extends('layouts.admin')

@section('title', 'Instructor Profile - ' . $trainer->user->name)
@section('page_title', 'Instructor Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.trainers.index') }}">Trainers</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $trainer->employee_id }}</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 mb-4 text-center">
            <div class="card-body p-4">
                <div class="avatar bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $trainer->user->name }}</h5>
                <p class="text-muted fs-8 mb-2">{{ $trainer->employee_id }}</p>

                @php
                    $badges = [
                        'available' => 'bg-success',
                        'busy' => 'bg-warning',
                        'on_leave' => 'bg-secondary',
                        'inactive' => 'bg-danger',
                    ];
                    $badge = $badges[$trainer->status] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badge }} mb-3 px-3 py-2 rounded-pill">{{ ucwords(str_replace('_', ' ', $trainer->status)) }}</span>

                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-address-card text-primary me-2"></i>Contact Details</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-envelope me-2"></i>Email</span>
                        <span class="fw-semibold text-dark">{{ $trainer->user->email }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-phone me-2"></i>Phone</span>
                        <span class="fw-semibold text-dark">{{ $trainer->user->phone ?? 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-briefcase text-primary me-2"></i>Professional Info</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-id-card me-2"></i>License #</span>
                        <span class="fw-semibold text-dark">{{ $trainer->license_number }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-calendar-xmark me-2"></i>Expiry</span>
                        <span class="fw-semibold text-dark">{{ $trainer->license_expiry ? $trainer->license_expiry->format('d M Y') : 'N/A' }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-star me-2"></i>Experience</span>
                        <span class="fw-semibold text-dark">{{ $trainer->experience_years }} Years</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-layer-group me-2"></i>Specialization</span>
                        <span class="fw-semibold text-dark text-end">{{ $trainer->specialization ?? 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" id="trainerTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                            <i class="fa-solid fa-calendar-check me-1"></i> Assigned Bookings ({{ $trainer->bookings->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                            <i class="fa-solid fa-users me-1"></i> Students Evaluated ({{ $trainer->progressEvaluations->groupBy('student_id')->count() }})
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <!-- Bookings Tab -->
                    <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Student</th>
                                        <th>Vehicle</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trainer->bookings as $booking)
                                    <tr>
                                        <td class="fw-semibold">{{ $booking->booking_date->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                                        <td>{{ $booking->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->vehicle->make ?? '' }} {{ $booking->vehicle->model ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'info') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No booking records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Students Evaluated Tab -->
                    <div class="tab-pane fade" id="students" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Latest Evaluation</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $uniqueStudents = $trainer->progressEvaluations->unique('student_id');
                                    @endphp
                                    @forelse($uniqueStudents as $eval)
                                    <tr>
                                        <td class="fw-semibold text-primary">{{ $eval->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $eval->skill_topic }} ({{ $eval->created_at->format('d M Y') }})</td>
                                        <td><span class="fw-bold">{{ $eval->score }}/5</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No student evaluations found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
