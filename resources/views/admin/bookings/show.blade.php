@extends('layouts.admin')

@section('title', 'Booking Details')
@section('page_title', 'Lesson Booking Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <!-- Booking Information -->
    <div class="col-12 col-xl-4 col-lg-5">
        <div class="card border-0 mb-4 h-100">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-day text-primary me-2"></i>Lesson Info</h6>
                <div>
                    @php
                        $statusBadges = [
                            'pending' => 'bg-warning',
                            'confirmed' => 'bg-info',
                            'completed' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            'no_show' => 'bg-secondary',
                        ];
                        $badgeClass = $statusBadges[$booking->status] ?? 'bg-dark';
                    @endphp
                    <span class="badge {{ $badgeClass }} px-2 py-1 rounded-pill">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-primary ms-2 rounded-pill px-3">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-calendar me-2"></i>Date</span>
                        <span class="fw-bold text-dark">{{ $booking->booking_date->format('l, d M Y') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-clock me-2"></i>Time Slot</span>
                        <span class="fw-bold text-dark text-primary">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-layer-group me-2"></i>Type</span>
                        <span class="fw-bold text-dark">{{ ucwords(str_replace('_', ' ', $booking->lesson_type)) }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                        <span class="text-muted"><i class="fa-solid fa-user-graduate me-2"></i>Student</span>
                        <div class="text-end">
                            <span class="fw-bold text-dark d-block">{{ $booking->student->user->name ?? 'N/A' }}</span>
                            <a href="{{ route('admin.students.show', $booking->student_id) }}" class="text-decoration-none text-2xs text-primary">View Profile</a>
                        </div>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="fa-solid fa-chalkboard-user me-2"></i>Trainer</span>
                        <div class="text-end">
                            <span class="fw-bold text-dark d-block">{{ $booking->trainer->user->name ?? 'N/A' }}</span>
                            <a href="{{ route('admin.trainers.show', $booking->trainer_id) }}" class="text-decoration-none text-2xs text-primary">View Profile</a>
                        </div>
                    </li>
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted"><i class="fa-solid fa-car-side me-2"></i>Vehicle</span>
                        <div class="text-end">
                            <span class="fw-bold text-dark d-block">{{ $booking->vehicle->make ?? '' }} {{ $booking->vehicle->model ?? 'N/A' }}</span>
                            <a href="{{ route('admin.vehicles.show', $booking->vehicle_id) }}" class="text-decoration-none text-2xs text-primary">{{ $booking->vehicle->registration_number ?? '' }}</a>
                        </div>
                    </li>
                </ul>

                @if($booking->notes)
                <div class="mt-3 p-3 bg-light rounded-3 text-muted fs-8 border">
                    <strong class="d-block mb-1 text-dark"><i class="fa-solid fa-note-sticky me-1"></i> Notes:</strong>
                    {{ $booking->notes }}
                </div>
                @endif

                @if($booking->status == 'cancelled' && $booking->cancellation_reason)
                <div class="mt-3 p-3 bg-danger-subtle text-danger-emphasis rounded-3 border border-danger-subtle fs-8">
                    <strong class="d-block mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Cancellation Reason:</strong>
                    {{ $booking->cancellation_reason }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8 col-lg-7">
        <div class="row g-4">

            <!-- Quick Status Change -->
            <div class="col-12">
                <div class="card border-0 bg-primary-subtle border border-primary-subtle">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h6 class="mb-0 fw-bold text-primary-emphasis"><i class="fa-solid fa-bolt me-2"></i>Quick Actions</h6>
                            <small class="text-primary-emphasis opacity-75">Update the lesson status instantly</small>
                        </div>
                        <form action="{{ route('admin.bookings.status.update', $booking->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm w-auto fw-bold" required>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="no_show" {{ $booking->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <input type="text" name="cancellation_reason" class="form-control form-control-sm d-none" id="quick_cancel_reason" placeholder="Reason...">
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Attendance & Progress Section -->
            <div class="col-12">
                <div class="card border-0 mb-0">
                    <div class="card-header bg-transparent border-bottom-0 pb-0">
                        <ul class="nav nav-tabs card-header-tabs" id="evaluationTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">
                                    <i class="fa-solid fa-clipboard-user me-1"></i> Attendance
                                    @if($booking->attendance)
                                        <i class="fa-solid fa-check-circle text-success ms-1"></i>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab">
                                    <i class="fa-solid fa-chart-line me-1"></i> Progress Evaluation
                                    @if($booking->progress)
                                        <i class="fa-solid fa-check-circle text-success ms-1"></i>
                                    @endif
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Attendance Tab -->
                            <div class="tab-pane fade show active" id="attendance" role="tabpanel">
                                @if($booking->attendance)
                                    <div class="alert bg-success-subtle border-success-subtle text-success-emphasis mb-4">
                                        <i class="fa-solid fa-check-circle me-2"></i> Attendance recorded on {{ $booking->attendance->created_at->format('d M Y h:i A') }}
                                    </div>
                                @endif

                                <form action="{{ route('admin.bookings.attendance.store', $booking->id) }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Attendance Status <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="att_present" value="present" {{ old('status', $booking->attendance->status ?? '') == 'present' ? 'checked' : '' }} required>
                                                    <label class="form-check-label text-success fw-bold" for="att_present">Present</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="att_absent" value="absent" {{ old('status', $booking->attendance->status ?? '') == 'absent' ? 'checked' : '' }} required>
                                                    <label class="form-check-label text-danger fw-bold" for="att_absent">Absent</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="att_late" value="late" {{ old('status', $booking->attendance->status ?? '') == 'late' ? 'checked' : '' }} required>
                                                    <label class="form-check-label text-warning fw-bold" for="att_late">Late</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Check-in Time</label>
                                            <input type="time" name="check_in_time" class="form-control" value="{{ old('check_in_time', $booking->attendance ? \Carbon\Carbon::parse($booking->attendance->check_in_time)->format('H:i') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Check-out Time</label>
                                            <input type="time" name="check_out_time" class="form-control" value="{{ old('check_out_time', $booking->attendance ? \Carbon\Carbon::parse($booking->attendance->check_out_time)->format('H:i') : '') }}">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Trainer Remarks</label>
                                            <textarea name="trainer_remarks" class="form-control" rows="2" placeholder="Any comments regarding attendance...">{{ old('trainer_remarks', $booking->attendance->trainer_remarks ?? '') }}</textarea>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-1"></i> {{ $booking->attendance ? 'Update' : 'Save' }} Record</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Progress Tab -->
                            <div class="tab-pane fade" id="progress" role="tabpanel">
                                @if($booking->progress)
                                    <div class="alert bg-success-subtle border-success-subtle text-success-emphasis mb-4">
                                        <i class="fa-solid fa-check-circle me-2"></i> Progress evaluated on {{ $booking->progress->created_at->format('d M Y h:i A') }}
                                        <hr class="mb-0 mt-2 border-success">
                                        <div class="mt-2 row text-dark">
                                            <div class="col-md-6">
                                                <strong>Topic:</strong> {{ $booking->progress->skill_topic }}<br>
                                                <strong>Score:</strong> {{ $booking->progress->score }} / 5<br>
                                                <strong>Competency:</strong>
                                                @php
                                                    $compMap = [
                                                        'needs_practice' => '<span class="badge bg-warning text-dark">Needs Practice</span>',
                                                        'proficient' => '<span class="badge bg-info">Proficient</span>',
                                                        'mastered' => '<span class="badge bg-success">Mastered</span>',
                                                    ];
                                                @endphp
                                                {!! $compMap[$booking->progress->competency_status] ?? '' !!}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Feedback:</strong><br>
                                                {{ $booking->progress->feedback ?: 'No feedback provided.' }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('admin.bookings.progress.store', $booking->id) }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Skill / Topic Covered <span class="text-danger">*</span></label>
                                                <input type="text" name="skill_topic" class="form-control" required placeholder="e.g., Parallel Parking, Highway Driving">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Score (1-5) <span class="text-danger">*</span></label>
                                                <select name="score" class="form-select" required>
                                                    <option value="1">1 - Poor</option>
                                                    <option value="2">2 - Fair</option>
                                                    <option value="3" selected>3 - Satisfactory</option>
                                                    <option value="4">4 - Good</option>
                                                    <option value="5">5 - Excellent</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Competency Status <span class="text-danger">*</span></label>
                                                <select name="competency_status" class="form-select" required>
                                                    <option value="">Select Status...</option>
                                                    <option value="needs_practice">Needs Practice</option>
                                                    <option value="proficient">Proficient</option>
                                                    <option value="mastered">Mastered</option>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Trainer Feedback</label>
                                                <textarea name="feedback" class="form-control" rows="3" placeholder="Detailed feedback on the student's performance..."></textarea>
                                            </div>

                                            <div class="col-12 text-end">
                                                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-1"></i> Submit Evaluation</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('select[name="status"]').on('change', function() {
            if($(this).val() === 'cancelled') {
                $('#quick_cancel_reason').removeClass('d-none').prop('required', true);
            } else {
                $('#quick_cancel_reason').addClass('d-none').prop('required', false);
            }
        });
    });
</script>
@endpush
