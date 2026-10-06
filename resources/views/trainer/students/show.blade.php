@extends('layouts.admin')

@section('title', 'Student Profile - ' . $student->user->name)
@section('page_title', 'Student Training Profile')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('trainer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('trainer.students.index') }}">My Students</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $student->user->name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center p-4">
                <div class="avatar-xl bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-2 mx-auto mb-3" style="width: 80px; height: 80px;">
                    {{ substr($student->user->name, 0, 1) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $student->user->name }}</h5>
                <p class="text-muted small mb-2">{{ $student->admission_number }}</p>
                <span class="badge bg-primary px-3 py-1">{{ strtoupper($student->license_type ?? 'LMV') }} Program</span>

                <div class="mt-4 text-start">
                    <h6 class="fw-bold small text-muted text-uppercase mb-3">Contact & Details</h6>
                    <div class="mb-2 small">
                        <i class="fa-solid fa-envelope text-muted me-2"></i> {{ $student->user->email }}
                    </div>
                    <div class="mb-2 small">
                        <i class="fa-solid fa-phone text-muted me-2"></i> {{ $student->user->phone ?? 'N/A' }}
                    </div>
                    <div class="mb-2 small">
                        <i class="fa-solid fa-calendar-check text-muted me-2"></i> Enrolled: {{ $student->enrollment_date ? $student->enrollment_date->format('d M, Y') : 'N/A' }}
                    </div>
                    <div class="mb-2 small">
                        <i class="fa-solid fa-heart-pulse text-muted me-2"></i> Emergency Contact: {{ $student->emergency_contact_name }} ({{ $student->emergency_contact }})
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 mt-4 text-center">
                    <div class="small text-muted mb-1">Average Evaluation Score</div>
                    <div class="display-6 fw-bold text-primary">{{ number_format($avgScore, 1) }}<span class="fs-6 text-muted">/10</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Driving Competency History -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chart-simple text-primary me-2"></i>Competency Evaluations</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Skill Topic</th>
                                <th>Competency</th>
                                <th>Score</th>
                                <th class="pe-4">Trainer Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $eval)
                                <tr>
                                    <td class="ps-4 small text-muted">{{ $eval->created_at->format('d M, Y') }}</td>
                                    <td class="fw-semibold">{{ $eval->skill_topic }}</td>
                                    <td>
                                        @php
                                            $badges = [
                                                'learning' => 'bg-warning text-dark',
                                                'improving' => 'bg-info text-dark',
                                                'competent' => 'bg-primary',
                                                'expert' => 'bg-success'
                                            ];
                                        @endphp
                                        <span class="badge {{ $badges[$eval->competency_status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($eval->competency_status) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-primary">{{ $eval->score }}/10</td>
                                    <td class="pe-4 small text-muted">{{ $eval->feedback }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">No evaluation recorded for this student yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lessons History with this Trainer -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Lesson History</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Vehicle</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th class="pe-4">Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">{{ $booking->booking_date->format('d M, Y') }}</div>
                                        <div class="small text-muted font-monospace">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        @if($booking->vehicle)
                                            <div>{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                            <div class="small text-muted">{{ $booking->vehicle->registration_number }}</div>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ ucfirst($booking->lesson_type) }}</span></td>
                                    <td>
                                        <span class="badge {{ $booking->status === 'completed' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-primary') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        @if($booking->attendance)
                                            <span class="badge {{ $booking->attendance->status === 'present' ? 'bg-success' : ($booking->attendance->status === 'late' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ ucfirst($booking->attendance->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border">Not Marked</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">No booked sessions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
