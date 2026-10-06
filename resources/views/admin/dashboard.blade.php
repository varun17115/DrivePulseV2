@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Overview')
@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Students Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-circle bg-primary-subtle text-primary" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-pill fw-bold text-3xs">
                        <i class="fa-solid fa-arrow-trend-up me-1"></i>+12%
                    </span>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Total Students</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_students']) }}</h2>
            </div>
        </div>
    </div>

    <!-- Active Trainers Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-circle bg-warning-subtle text-warning" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Active Trainers</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['active_trainers']) }}</h2>
            </div>
        </div>
    </div>

    <!-- Active Vehicles Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-circle bg-info-subtle text-info" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-car"></i>
                    </div>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Active Vehicles</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ number_format($stats['active_vehicles']) }}</h2>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-circle bg-success-subtle text-success" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-pill fw-bold text-3xs">
                        <i class="fa-solid fa-arrow-trend-up me-1"></i>+8.5%
                    </span>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Revenue ({{ now()->format('M') }})</h6>
                <h2 class="fw-bold mb-0 text-dark">₹{{ number_format($stats['monthly_revenue'], 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-12 col-xl-8">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom-0 pb-0">
                <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-chart-line text-primary me-2"></i>Revenue Analytics</h6>
                <select class="form-select form-select-sm shadow-none border-secondary-subtle" style="width: 120px;">
                    <option value="{{ now()->year }}">Year {{ now()->year }}</option>
                    <option value="{{ now()->year - 1 }}">Year {{ now()->year - 1 }}</option>
                </select>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Vehicle Status Chart -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent mb-0 border-bottom-0 pb-0">
                <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-chart-pie text-secondary me-2"></i>Fleet Status</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 300px;">
                <canvas id="fleetChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Upcoming Bookings -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">Upcoming Lessons</h6>
                <a href="{{ Route::has('admin.bookings.index') ? route('admin.bookings.index') : '#' }}" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Student</th>
                                <th>Date/Time</th>
                                <th>Trainer</th>
                                <th>Vehicle</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingBookings as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="fw-semibold text-dark fs-8">{{ $booking->student->user->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-8 fw-bold text-primary">{{ $booking->booking_date->format('d M, Y') }}</div>
                                        <div class="text-muted text-3xs">{{ Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="fs-8">{{ $booking->trainer->user->name }}</span>
                                    </td>
                                    <td>
                                        <div class="fs-8">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                        <div class="text-muted text-3xs">{{ $booking->vehicle->registration_number }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill text-3xs">Scheduled</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No upcoming lessons scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">Recent Enrollments</h6>
                <a href="{{ Route::has('admin.students.index') ? route('admin.students.index') : '#' }}" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill px-3">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentStudents as $student)
                    <div class="list-group-item px-4 py-3 d-flex align-items-center justify-content-between border-0 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $student->user->avatar ? asset('storage/'.$student->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($student->user->name).'&background=4f46e5&color=fff&bold=true' }}"
                                 class="rounded-circle border border-2 border-primary-subtle" width="40" height="40" alt="{{ $student->user->name }}">
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark fs-8">{{ $student->user->name }}</h6>
                                <p class="mb-0 text-muted text-3xs">{{ $student->admission_number }}</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-secondary border rounded-pill text-3xs">{{ $student->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        No recent enrollments.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Chart.js Defaults
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = "#64748b";

    // 1. Revenue Bar/Line Chart
    const revCtx = document.getElementById('revenueChart').getContext('2d');

    // Create gradient
    let gradient = revCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! json_encode(array_values($revenueData)) !!},
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // smooth curves
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: { callback: function(value) { return '₹' + value; } }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });

    // 2. Fleet Status Doughnut Chart
    const fleetCtx = document.getElementById('fleetChart').getContext('2d');
    new Chart(fleetCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'In Maintenance', 'Inactive'],
            datasets: [{
                data: [{{ $vehicleStats['active'] }}, {{ $vehicleStats['maintenance'] }}, {{ $vehicleStats['inactive'] }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 20 }
                }
            }
        }
    });
});
</script>
@endpush
