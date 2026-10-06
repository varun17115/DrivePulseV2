@extends('layouts.admin')

@section('title', 'Student Profile - ' . $student->user->name)
@section('page_title', 'Student Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $student->admission_number }}</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Left Profile Card -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 mb-4 text-center">
            <div class="card-body p-4">
                <div class="avatar bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $student->user->name }}</h5>
                <p class="text-muted fs-8 mb-2">{{ $student->admission_number }}</p>

                @php
                    $badges = [
                        'enrolled' => 'bg-info',
                        'in_progress' => 'bg-primary',
                        'completed' => 'bg-success',
                        'dropped' => 'bg-danger',
                    ];
                    $badge = $badges[$student->course_status] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badge }} mb-3 px-3 py-2 rounded-pill">{{ ucwords(str_replace('_', ' ', $student->course_status)) }}</span>

                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('admin.students.admission-form', $student->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-file-contract me-1"></i> Admission Form
                    </a>
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Personal & Contact Info -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-address-card text-primary me-2"></i>Contact Details</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-envelope me-2"></i>Email</span>
                        <span class="fw-semibold text-dark">{{ $student->user->email }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-phone me-2"></i>Phone</span>
                        <span class="fw-semibold text-dark">{{ $student->user->phone ?? 'N/A' }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-id-card me-2"></i>License</span>
                        <span class="fw-semibold text-dark">{{ $student->license_type }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-calendar me-2"></i>Enrolled</span>
                        <span class="fw-semibold text-dark">{{ $student->enrollment_date ? $student->enrollment_date->format('d M Y') : 'N/A' }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-venus-mars me-2"></i>Gender</span>
                        <span class="fw-semibold text-dark">{{ ucfirst($student->gender ?? 'N/A') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-cake-candles me-2"></i>DOB</span>
                        <span class="fw-semibold text-dark">{{ $student->dob ? $student->dob->format('d M Y') : 'N/A' }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-location-dot me-2"></i>Address</span>
                        <span class="fw-semibold text-dark text-end">{{ $student->address ?? 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-kit-medical text-danger me-2"></i>Emergency Contact</h6>
            </div>
            <div class="card-body">
                <p class="mb-1 fw-bold text-dark">{{ $student->emergency_contact_name ?? 'Not provided' }}</p>
                <p class="text-muted mb-0"><i class="fa-solid fa-phone me-2"></i>{{ $student->emergency_contact ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Right Tabs Area -->
    <div class="col-12 col-lg-8">
        <!-- Financial Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <span class="text-white-50 fs-8">Total Fee</span>
                        <h4 class="fw-bold mb-0 text-white">₹{{ number_format($student->total_amount, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <span class="text-white-50 fs-8">Total Paid</span>
                        <h4 class="fw-bold mb-0 text-white">₹{{ number_format($student->paid_amount, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 bg-danger text-white">
                    <div class="card-body">
                        <span class="text-white-50 fs-8">Due Amount</span>
                        <h4 class="fw-bold mb-0 text-white">₹{{ number_format($student->due_amount, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tab Navigation -->
        <div class="card border-0">
            <div class="card-header bg-transparent border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" id="studentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                            <i class="fa-solid fa-calendar-check me-1"></i> Bookings ({{ $student->bookings->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                            <i class="fa-solid fa-receipt me-1"></i> Payments ({{ $student->payments->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab">
                            <i class="fa-solid fa-chart-line me-1"></i> Progress Evaluations
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="studentTabContent">
                    <!-- Bookings Tab -->
                    <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Trainer</th>
                                        <th>Vehicle</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->bookings as $booking)
                                    <tr>
                                        <td class="fw-semibold">{{ $booking->booking_date->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                                        <td>{{ $booking->trainer->user->name ?? 'N/A' }}</td>
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

                    <!-- Payments Tab -->
                    <div class="tab-pane fade" id="payments" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->payments as $payment)
                                    <tr>
                                        <td class="fw-semibold text-primary">{{ $payment->invoice_number }}</td>
                                        <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y') : $payment->created_at->format('d M Y') }}</td>
                                        <td class="fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No payment records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Progress Tab -->
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Skill / Topic</th>
                                        <th>Score</th>
                                        <th>Remarks</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->progress as $prog)
                                    <tr>
                                        <td class="fw-semibold">
                                            {{ $prog->skill_topic }}
                                            <div>
                                                @php
                                                    $compBadges = [
                                                        'needs_practice' => 'bg-warning-subtle text-warning-emphasis',
                                                        'proficient' => 'bg-info-subtle text-info-emphasis',
                                                        'mastered' => 'bg-success-subtle text-success-emphasis',
                                                    ];
                                                @endphp
                                                <span class="badge {{ $compBadges[$prog->competency_status] ?? 'bg-secondary' }} fs-9">
                                                    {{ ucwords(str_replace('_', ' ', $prog->competency_status)) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ ($prog->score ?? 0) * 20 }}%"></div>
                                                </div>
                                                <span class="fw-bold fs-8">{{ $prog->score ?? '0' }}/5</span>
                                            </div>
                                        </td>
                                        <td>{{ $prog->feedback ?? '-' }}</td>
                                        <td>{{ $prog->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No progress evaluations logged yet.</td>
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
