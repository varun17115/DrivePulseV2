@extends('layouts.admin')

@section('title', 'Lesson Bookings')
@section('page_title', 'Lesson Bookings')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Bookings</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-check text-primary me-2"></i>Booking Management</h6>
        <div>
            <a href="{{ route('admin.bookings.calendar') }}" class="btn btn-outline-info btn-sm rounded-pill px-3 me-2">
                <i class="fa-solid fa-calendar-days me-1"></i> Calendar View
            </a>
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Add Booking
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="bookingsTable">
                <thead class="bg-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Student</th>
                        <th>Trainer</th>
                        <th>Lesson Type</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
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
        $('#bookingsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.bookings.index') }}",
            columns: [
                { data: 'date_time', name: 'booking_date' },
                { data: 'student', name: 'student.user.name' },
                { data: 'trainer', name: 'trainer.user.name' },
                { data: 'lesson_badge', name: 'lesson_type' },
                { data: 'status_badge', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
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

        $(document).on('click', '.btn-delete', function() {
            let url = $(this).data('url');
            let name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete " + name,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#bookingsTable').DataTable().ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
