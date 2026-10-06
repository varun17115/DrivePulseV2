@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page_title', 'Reports & Analytics')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</li>
@endsection

@section('content')
<!-- Export Reports Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-file-export me-2"></i> Instant Report Exports (PDF & Excel/CSV)</h6>
                    <span class="badge bg-primary-subtle text-primary">7 Modules Available</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Students Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-user-graduate text-primary me-2"></i> Students Report</div>
                                <small class="text-muted d-block mb-3">Enrolled students, fees, dues & contact details.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'students', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'students', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Trainers Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-chalkboard-user text-info me-2"></i> Trainers Report</div>
                                <small class="text-muted d-block mb-3">Instructor profiles, experience & salaries.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'trainers', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'trainers', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-clipboard-user text-warning me-2"></i> Attendance Report</div>
                                <small class="text-muted d-block mb-3">Student lesson attendance logs & statuses.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'attendance', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'attendance', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Payments Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-credit-card text-success me-2"></i> Payments Report</div>
                                <small class="text-muted d-block mb-3">All revenue transactions, invoices & modes.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'payments', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'payments', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mock Tests Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-list-check text-purple me-2"></i> Mock Tests Report</div>
                                <small class="text-muted d-block mb-3">Theory test scores, pass rates & duration.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'mock_tests', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'mock_tests', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicles Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-car-side text-secondary me-2"></i> Vehicles Report</div>
                                <small class="text-muted d-block mb-3">Fleet status, insurance & service costs.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'vehicles', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'vehicles', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Certificates Report -->
                    <div class="col-md-4 col-lg-3">
                        <div class="p-3 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-award text-warning me-2"></i> Certificates Report</div>
                                <small class="text-muted d-block mb-3">Issued certificates, dates & verification URLs.</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reports.export', ['type' => 'certificates', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success flex-fill">
                                    <i class="fa-solid fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('admin.reports.export', ['type' => 'certificates', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger flex-fill">
                                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-2"></i> Filter Analytics</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <h6 class="text-white-50 text-uppercase fw-bold mb-2">Total Revenue</h6>
                <h3 class="fw-bold mb-0">₹{{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-danger text-white">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <h6 class="text-white-50 text-uppercase fw-bold mb-2">Vehicle Maintenance Costs</h6>
                <h3 class="fw-bold mb-0">₹{{ number_format($totalMaintenance, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-success text-white">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <h6 class="text-white-50 text-uppercase fw-bold mb-2">Net Income</h6>
                <h3 class="fw-bold mb-0">₹{{ number_format($netIncome, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Financial Overview (Revenue vs Expenses)</h6>
            </div>
            <div class="card-body" style="height: 320px; position: relative;">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Booking Status Distributions</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 320px; position: relative;">
                <canvas id="bookingStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Student Enrollments Growth</h6>
            </div>
            <div class="card-body" style="height: 280px; position: relative;">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Mock Test Performance</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 280px; position: relative;">
                <canvas id="testPerformanceChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const financialCtx = document.getElementById('financialChart').getContext('2d');
        const bookingCtx = document.getElementById('bookingStatusChart').getContext('2d');
        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
        const testCtx = document.getElementById('testPerformanceChart').getContext('2d');

        // Prepare Data for Financial Overview
        const financialMonths = [...new Set([
            ...Object.keys(@json($monthlyRevenue)),
            ...Object.keys(@json($monthlyExpenses))
        ])].sort();

        const revenueData = financialMonths.map(m => @json($monthlyRevenue)[m] || 0);
        const expenseData = financialMonths.map(m => @json($monthlyExpenses)[m] || 0);

        new Chart(financialCtx, {
            type: 'line',
            data: {
                labels: financialMonths,
                datasets: [
                    {
                        label: 'Revenue',
                        data: revenueData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Maintenance Expenses',
                        data: expenseData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // Booking Status Pie
        const bookingStatusData = @json($bookingStatus);
        new Chart(bookingCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(bookingStatusData),
                datasets: [{
                    data: Object.values(bookingStatusData),
                    backgroundColor: ['#eab308', '#2563eb', '#22c55e', '#ef4444']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Enrollment Bar
        const enrollmentData = @json($studentEnrollments);
        new Chart(enrollmentCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(enrollmentData),
                datasets: [{
                    label: 'New Students',
                    data: Object.values(enrollmentData),
                    backgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Mock Test Performance Pie
        const mockTestData = @json($mockTestStats);
        new Chart(testCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(mockTestData),
                datasets: [{
                    data: Object.values(mockTestData),
                    backgroundColor: ['#22c55e', '#ef4444']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endpush
