@extends('layouts.admin')

@section('title', 'Booking Calendar')
@section('page_title', 'Booking Calendar')

@section('styles')
<style>
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 4px;
    }
    .fc-event:hover {
        opacity: 0.9;
    }
    .fc-theme-bootstrap5 .fc-daygrid-day-number {
        text-decoration: none;
        color: #475569;
    }
    .fc-theme-bootstrap5 .fc-col-header-cell-cushion {
        text-decoration: none;
        color: #1e293b;
        font-weight: 600;
    }
</style>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Calendar</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-regular fa-calendar-days text-primary me-2"></i>Schedule Overview</h6>
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2">
                <i class="fa-solid fa-list me-1"></i> List View
            </a>
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Add Booking
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <!-- Legend -->
        <div class="d-flex flex-wrap gap-3 mb-4 fs-8">
            <div class="d-flex align-items-center"><span class="badge bg-warning me-2">&nbsp;</span> Pending</div>
            <div class="d-flex align-items-center"><span class="badge bg-info me-2">&nbsp;</span> Confirmed</div>
            <div class="d-flex align-items-center"><span class="badge bg-success me-2">&nbsp;</span> Completed</div>
            <div class="d-flex align-items-center"><span class="badge bg-danger me-2">&nbsp;</span> Cancelled</div>
            <div class="d-flex align-items-center"><span class="badge bg-secondary me-2">&nbsp;</span> No Show</div>
        </div>

        <div id="calendar"></div>
    </div>
</div>

<!-- Event Click Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold" id="eventTitle">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-user-graduate me-2"></i>Student</span>
                        <span class="fw-semibold text-dark" id="eventStudent"></span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-chalkboard-user me-2"></i>Trainer</span>
                        <span class="fw-semibold text-dark" id="eventTrainer"></span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-regular fa-clock me-2"></i>Time</span>
                        <span class="fw-semibold text-dark" id="eventTime"></span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-circle-info me-2"></i>Status</span>
                        <span class="fw-semibold text-dark" id="eventStatus"></span>
                    </li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="#" id="eventUrl" class="btn btn-primary btn-sm rounded-pill px-4">
                        View Full Details <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            navLinks: true,
            nowIndicator: true,
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            events: '{{ route('admin.bookings.calendar') }}',
            eventClick: function(info) {
                // Populate Modal Data
                $('#eventStudent').text(info.event.title);
                $('#eventTrainer').text(info.event.extendedProps.trainer);

                // Format times
                let start = info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                let end = info.event.end ? info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
                $('#eventTime').text(start + (end ? ' - ' + end : ''));

                $('#eventStatus').text(info.event.extendedProps.status);
                $('#eventUrl').attr('href', info.event.extendedProps.url);

                eventModal.show();
            }
        });

        calendar.render();
    });
</script>
@endpush
