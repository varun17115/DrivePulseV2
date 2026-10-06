@extends('layouts.admin')

@section('title', 'Trainer Dashboard')
@section('page_title', 'Trainer Workspace')
@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card bg-primary text-white border-0">
            <div class="card-body">
                <h6 class="text-white-50 text-uppercase fw-semibold text-xs mb-1">Today's Lessons</h6>
                <h2 class="fw-bold mb-0 text-white">{{ $stats['today_lessons'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Upcoming</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['upcoming_lessons'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Total Students</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['total_students'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold text-xs mb-1">Lessons(This Week)</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['hours_this_week'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Today's Schedule -->
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-day text-primary me-2"></i>Today's Schedule ({{ now()->format('d M, Y') }})</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="bg-light">
                        <th>Time</th>
                        <th>Student</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todaySchedule as $booking)
                    <tr>
                        <td class="fw-bold text-primary">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                        <td>{{ $booking->student->user->name }}</td>
                        <td>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</td>
                        <td><span class="badge bg-info text-white">{{ ucfirst($booking->status) }}</span></td>
                        <td>
                            <a href="{{ route('trainer.progress.index') }}" class="btn btn-sm btn-primary">Evaluation</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No lessons scheduled for today.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
