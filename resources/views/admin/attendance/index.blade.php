@extends('layouts.admin')

@section('title', 'Attendance Logs')
@section('page_title', 'System-wide Attendance Logs')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Attendance Logs</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-clipboard-user text-primary me-2"></i>All Attendance Records</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="attendanceTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Lesson Date/Time</th>
                        <th>Student</th>
                        <th>Trainer</th>
                        <th>Status</th>
                        <th>Check-in</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.attendance.index') }}",
            columns: [
                { data: 'date_time', name: 'booking.booking_date' },
                { data: 'student', name: 'booking.student.user.name', orderable: false },
                { data: 'trainer', name: 'booking.trainer.user.name', orderable: false },
                { data: 'status_badge', name: 'status' },
                { data: 'check_in', name: 'check_in_time' },
                { data: 'remarks', name: 'trainer_remarks', orderable: false }
            ],
            order: [[0, 'desc']],
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center gap-2'lB><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",
            buttons: {
                dom: {
                    button: {
                        className: 'btn'
                    }
                },
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="fa-solid fa-file-excel me-1"></i> Excel', className: 'btn-sm btn-outline-success mb-2 mb-md-0' },
                    { extend: 'pdfHtml5', text: '<i class="fa-solid fa-file-pdf me-1"></i> PDF', className: 'btn-sm btn-outline-danger mb-2 mb-md-0' },
                    { extend: 'print', text: '<i class="fa-solid fa-print me-1"></i> Print', className: 'btn-sm btn-outline-secondary mb-2 mb-md-0' }
                ]
            }
        });
    });
</script>
@endpush
