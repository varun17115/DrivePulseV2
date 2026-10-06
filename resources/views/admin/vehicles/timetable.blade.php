@extends('layouts.admin')

@section('title', 'Vehicle Timetable Matrix')
@section('page_title', 'Fleet Schedule & Vehicle Timetable Matrix')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.vehicles.index') }}">Vehicles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Timetable Matrix</li>
@endsection

@push('styles')
<style>
    .timetable-table {
        table-layout: fixed;
        min-width: 900px;
    }
    .timetable-time-col {
        width: 140px;
        background-color: #f8fafc;
        font-weight: 700;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .slot-cell {
        height: 64px;
        vertical-align: middle;
        padding: 6px !important;
        transition: background 0.15s;
    }
    .slot-cell:hover {
        background-color: #f1f5f9;
    }
    .slot-card-booked {
        background: #eff6ff;
        border-left: 4px solid #2563eb;
        border-radius: 8px;
        padding: 6px 10px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .slot-card-available {
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .slot-card-available:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
        color: #2563eb;
    }
</style>
@endpush

@section('content')
<!-- Header Stats & Date Bar -->
<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center justify-content-between flex-wrap gap-3">
            <!-- Day Navigation -->
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.vehicles.timetable', ['date' => $prevDate]) }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold">
                    <i class="fa-solid fa-chevron-left me-1"></i> Prev Day
                </a>
                <a href="{{ route('admin.vehicles.timetable', ['date' => now()->toDateString()]) }}" class="btn {{ $isToday ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3 fw-bold">
                    Today
                </a>
                <a href="{{ route('admin.vehicles.timetable', ['date' => $nextDate]) }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold">
                    Next Day <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>

            <!-- Date Picker Form -->
            <form action="{{ route('admin.vehicles.timetable') }}" method="GET" class="d-flex align-items-center gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fa-regular fa-calendar text-primary"></i></span>
                    <input type="date" name="date" class="form-control border-start-0 rounded-end-pill shadow-none fw-semibold" value="{{ $selectedDate->toDateString() }}" onchange="this.form.submit()">
                </div>
            </form>

            <div class="fs-5 fw-bold text-dark font-monospace">
                {{ $selectedDate->format('l, M d, Y') }}
            </div>
        </div>
    </div>

    <!-- Utilization Rate KPI -->
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-dark text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold">Fleet Utilization</div>
                    <div class="fs-3 fw-bold font-monospace text-emerald-400 text-success">{{ $occupancyRate }}%</div>
                    <small class="text-white-50">{{ $totalBookedSlots }} / {{ $totalCapacitySlots }} slots utilized</small>
                </div>
                <div class="fs-1 text-white opacity-25">
                    <i class="fa-solid fa-car-side"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timetable Matrix Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-table-cells text-primary me-2"></i>Daily Slot Allocation by Vehicle</h5>
            <p class="text-muted small mb-0">Hourly breakdown of fleet bookings across {{ count($vehicles) }} active vehicle(s)</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fa-solid fa-square text-white me-1"></i> Booked</span>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill"><i class="fa-regular fa-square me-1"></i> Available Slot</span>
        </div>
    </div>
    <div class="card-body p-4">
        @if($vehicles->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-car-tunnel fa-3x mb-3 text-secondary opacity-50"></i>
                <h5 class="fw-bold">No Active Vehicles Found</h5>
                <p>Add active vehicles in the Fleet Management module to generate timetable matrices.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered timetable-table mb-0 align-middle">
                    <thead>
                        <tr class="bg-light text-center">
                            <th class="timetable-time-col">Time Slot</th>
                            @foreach($vehicles as $vehicle)
                                <th class="py-3">
                                    <div class="fw-bold text-dark fs-6">{{ $vehicle->name }}</div>
                                    <div class="badge bg-secondary-subtle text-secondary font-monospace">{{ $vehicle->plate_number }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $slotTime => $slotLabel)
                            <tr>
                                <td class="timetable-time-col text-center">
                                    <div class="font-monospace">{{ $slotLabel }}</div>
                                </td>
                                @foreach($vehicles as $vehicle)
                                    @php $booking = $timetableGrid[$vehicle->id][$slotTime] ?? null; @endphp
                                    <td class="slot-cell">
                                        @if($booking)
                                            <div class="slot-card-booked shadow-sm">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-primary text-white text-3xs">{{ strtoupper($booking->lesson_type ?? 'Practical') }}</span>
                                                    <span class="badge {{ $booking->status === 'completed' ? 'bg-success' : 'bg-info' }} text-white text-3xs">{{ ucfirst($booking->status) }}</span>
                                                </div>
                                                <div class="fw-bold text-dark text-truncate small" title="{{ $booking->student->user->name ?? 'Student' }}">
                                                    <i class="fa-solid fa-user-graduate me-1 text-primary"></i> {{ $booking->student->user->name ?? 'Student' }}
                                                </div>
                                                <div class="text-muted text-2xs text-truncate" title="{{ $booking->trainer->user->name ?? 'Trainer' }}">
                                                    <i class="fa-solid fa-chalkboard-user me-1 text-secondary"></i> {{ $booking->trainer->user->name ?? 'Trainer' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="slot-card-available btn-quick-book" 
                                                 data-vehicle-id="{{ $vehicle->id }}" 
                                                 data-vehicle-name="{{ $vehicle->name }} ({{ $vehicle->plate_number }})"
                                                 data-time="{{ $slotTime }}"
                                                 data-date="{{ $selectedDate->toDateString() }}">
                                                <i class="fa-solid fa-plus me-1"></i> Available
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Quick Booking Modal -->
<div class="modal fade" id="quickBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-primary text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-calendar-plus me-2"></i>Quick Book Lesson Slot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" id="modal_vehicle_id">
                <input type="hidden" name="booking_date" id="modal_booking_date">
                <input type="hidden" name="start_time" id="modal_start_time">
                <input type="hidden" name="end_time" id="modal_end_time">

                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-car-side fs-4 text-primary"></i>
                        <div>
                            <strong id="modal_vehicle_display">Vehicle</strong><br>
                            <span class="small font-monospace" id="modal_slot_display">Date & Time</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Student <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select rounded-3 shadow-none" required>
                            <option value="">-- Choose Student --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}">{{ $s->user->name ?? 'Student' }} ({{ $s->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Trainer / Instructor <span class="text-danger">*</span></label>
                        <select name="trainer_id" class="form-select rounded-3 shadow-none" required>
                            <option value="">-- Choose Trainer --</option>
                            @foreach($trainers as $t)
                                <option value="{{ $t->id }}">{{ $t->user->name ?? 'Trainer' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lesson Type <span class="text-danger">*</span></label>
                        <select name="lesson_type" class="form-select rounded-3 shadow-none" required>
                            <option value="practical">Practical Driving Lesson</option>
                            <option value="theory">Theory Session</option>
                            <option value="simulator">Simulator Training</option>
                            <option value="mock_test">Road Test Prep</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Reserve Slot</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const bookModal = new bootstrap.Modal(document.getElementById('quickBookingModal'));

    $('.btn-quick-book').on('click', function() {
        const vehicleId = $(this).data('vehicle-id');
        const vehicleName = $(this).data('vehicle-name');
        const timeSlot = $(this).data('time');
        const date = $(this).data('date');

        $('#modal_vehicle_id').val(vehicleId);
        $('#modal_booking_date').val(date);
        
        // Calculate start and end timestamp (1 hour duration)
        const startIso = `${date} ${timeSlot}`;
        const hour = parseInt(timeSlot.split(':')[0]);
        const endIso = `${date} ${(hour + 1).toString().padStart(2, '0')}:00:00`;

        $('#modal_start_time').val(startIso);
        $('#modal_end_time').val(endIso);

        $('#modal_vehicle_display').text(vehicleName);
        $('#modal_slot_display').text(`${date} @ ${timeSlot}`);

        bookModal.show();
    });
});
</script>
@endpush
