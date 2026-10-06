@extends('layouts.admin')

@section('title', 'Student Dashboard')
@section('page_title', 'My Learning Dashboard')
@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Welcome Banner -->
    <div class="col-12">
        <div class="card border-0" style="background: linear-gradient(135deg, #4f46e5, #0ea5e9);">
            <div class="card-body text-white p-4">
                <h4 class="fw-bold mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
                <p class="text-white-50 mb-3">Here's an overview of your driving course progress.</p>
                <!-- Progress Bar -->
                <div class="d-flex align-items-center gap-3 mt-2">
                    <div class="flex-grow-1" style="max-width: 400px;">
                        <div class="progress rounded-pill" role="progressbar" style="height: 10px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-white rounded-pill" style="width: {{ $stats['progress_percent'] }}%"></div>
                        </div>
                    </div>
                    <span class="fw-bold fs-5 text-white">{{ $stats['progress_percent'] }}%</span>
                </div>
                <p class="text-white-50 mt-1 mb-0 text-xs">Course Progress: {{ $stats['completed_lessons'] }}/{{ $stats['total_lessons'] }} lessons completed</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stats -->
    <div class="col-12 col-md-4">
        <div class="card border-0">
            <div class="card-body text-center">
                <div class="icon-circle bg-primary-subtle text-primary mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.3rem;">
                    <i class="fa-solid fa-road"></i>
                </div>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['completed_lessons'] }}</h2>
                <p class="text-muted fw-medium mb-0">Lessons Completed</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0">
            <div class="card-body text-center">
                <div class="icon-circle bg-warning-subtle text-warning mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.3rem;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['total_lessons'] }}</h2>
                <p class="text-muted fw-medium mb-0">Total Lessons Booked</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0">
            <div class="card-body text-center">
                <div class="icon-circle bg-danger-subtle text-danger mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.3rem;">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h2 class="fw-bold mb-0 text-dark">₹{{ number_format($stats['due_payments'], 2) }}</h2>
                <p class="text-muted fw-medium mb-0">Fees Due</p>
            </div>
        </div>
    </div>
</div>

<!-- Next Upcoming Booking -->
<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card border-0">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-day text-primary me-2"></i>Next Lesson</h6>
            </div>
            <div class="card-body">
                @if($upcomingBooking)
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-circle bg-info-subtle text-info flex-shrink-0" style="width: 50px; height: 50px; font-size: 1.3rem;">
                            <i class="fa-solid fa-steering-wheel"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $upcomingBooking->booking_date->format('l, d M Y') }}</h6>
                            <p class="text-muted mb-1 fs-8">
                                <i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($upcomingBooking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($upcomingBooking->end_time)->format('h:i A') }}
                            </p>
                            <p class="text-muted mb-1 fs-8">
                                <i class="fa-solid fa-user me-1"></i>Trainer: {{ $upcomingBooking->trainer->user->name }}
                            </p>
                            <p class="text-muted mb-0 fs-8">
                                <i class="fa-solid fa-car me-1"></i>Vehicle: {{ $upcomingBooking->vehicle->make }} {{ $upcomingBooking->vehicle->model }} ({{ $upcomingBooking->vehicle->transmission_type }})
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="fa-regular fa-calendar-xmark fa-2x mb-2 text-secondary"></i>
                        <p class="mb-0">No upcoming lessons scheduled.</p>
                        <a href="{{ Route::has('student.bookings.create') ? route('student.bookings.create') : '#' }}" class="btn btn-sm btn-primary mt-3 rounded-pill px-4">Book a Lesson</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card border-0">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>Quick Links</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ Route::has('student.bookings.index') ? route('student.bookings.index') : '#' }}" class="d-block text-decoration-none bg-light rounded-4 p-3 text-center hover-shadow">
                            <i class="fa-solid fa-calendar-plus text-primary fs-4 mb-2 d-block"></i>
                            <span class="fw-semibold text-dark fs-8">Book Lessons</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ Route::has('student.progress.index') ? route('student.progress.index') : '#' }}" class="d-block text-decoration-none bg-light rounded-4 p-3 text-center hover-shadow">
                            <i class="fa-solid fa-chart-line text-success fs-4 mb-2 d-block"></i>
                            <span class="fw-semibold text-dark fs-8">My Progress</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ Route::has('student.mock-tests.index') ? route('student.mock-tests.index') : '#' }}" class="d-block text-decoration-none bg-light rounded-4 p-3 text-center hover-shadow">
                            <i class="fa-solid fa-laptop-code text-warning fs-4 mb-2 d-block"></i>
                            <span class="fw-semibold text-dark fs-8">Mock Tests</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ Route::has('student.payments.index') ? route('student.payments.index') : '#' }}" class="d-block text-decoration-none bg-light rounded-4 p-3 text-center hover-shadow">
                            <i class="fa-solid fa-file-invoice-dollar text-danger fs-4 mb-2 d-block"></i>
                            <span class="fw-semibold text-dark fs-8">Payments</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <!-- Recent Evaluations -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-clipboard-check text-success me-2"></i>Recent Evaluations</h6>
                <a href="{{ Route::has('student.progress.index') ? route('student.progress.index') : '#' }}" class="text-decoration-none fs-8 fw-medium">View All</a>
            </div>
            <div class="card-body">
                @if($recentEvaluations->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($recentEvaluations as $eval)
                            <div class="p-3 bg-light rounded-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ ucfirst($eval->skill_topic) }}</h6>
                                        <span class="badge {{ $eval->competency_status === 'proficient' ? 'bg-success-subtle text-success' : ($eval->competency_status === 'needs_improvement' ? 'bg-warning-subtle text-warning' : 'bg-primary-subtle text-primary') }} rounded-pill border-0 px-2 py-1 fs-9">
                                            {{ ucwords(str_replace('_', ' ', $eval->competency_status)) }}
                                        </span>
                                    </div>
                                    <div class="d-flex text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $eval->score ? 'fa-solid' : 'fa-regular' }} fa-star fs-8"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($eval->feedback)
                                    <p class="mb-1 text-muted fs-8 fst-italic">"{{ Str::limit($eval->feedback, 100) }}"</p>
                                @endif
                                <div class="text-muted fs-9 mt-2 d-flex justify-content-between">
                                    <span><i class="fa-solid fa-user-tie me-1"></i>{{ $eval->trainer->user->name ?? 'Trainer' }}</span>
                                    <span>{{ $eval->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fa-regular fa-clipboard fa-2x mb-2 text-secondary"></i>
                        <p class="mb-0 fs-8">No evaluations recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-alt text-primary me-2"></i>Recent Bookings</h6>
                <a href="{{ Route::has('student.bookings.index') ? route('student.bookings.index') : '#' }}" class="text-decoration-none fs-8 fw-medium">View All</a>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle fs-8">
                            <thead class="text-muted border-bottom">
                                <tr>
                                    <th class="fw-medium pb-2">Date & Time</th>
                                    <th class="fw-medium pb-2">Lesson Type</th>
                                    <th class="fw-medium pb-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $booking->booking_date->format('M d, Y') }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</div>
                                        </td>
                                        <td>
                                            <span class="text-capitalize">{{ $booking->lesson_type }}</span>
                                            <div class="text-muted" style="font-size: 0.75rem;">Trainer: {{ $booking->trainer->user->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill border-0 px-2 py-1
                                                {{ $booking->status === 'completed' ? 'bg-success-subtle text-success' :
                                                   ($booking->status === 'cancelled' ? 'bg-danger-subtle text-danger' :
                                                   ($booking->status === 'confirmed' ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning')) }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fa-regular fa-calendar fa-2x mb-2 text-secondary"></i>
                        <p class="mb-0 fs-8">No recent bookings found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
