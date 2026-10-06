@extends('layouts.admin')

@section('title', 'Instructors / Trainers')
@section('page_title', 'Trainer Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Trainers</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chalkboard-user text-primary me-2"></i>Active Instructors</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trainers.export') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel/CSV
            </a>
            <a href="{{ route('admin.trainers.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Onboard Trainer
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="trainersTable">
                <thead class="bg-light">
                    <tr>
                        <th>Employee ID</th>
                        <th>Instructor Name</th>
                        <th>Phone / Email</th>
                        <th>License #</th>
                        <th>Experience</th>
                        <th>Rate/Hr</th>
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
        $('#trainersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.trainers.index') }}",
            columns: [
                { data: 'employee_id', name: 'employee_id', className: 'fw-semibold text-primary' },
                { data: 'name', name: 'user.name', className: 'fw-bold' },
                {
                    data: null,
                    name: 'user.phone',
                    render: function(data, type, row) {
                        return row.phone + '<br><small class="text-muted">' + row.email + '</small>';
                    }
                },
                { data: 'license_number', name: 'license_number' },
                { data: 'experience', name: 'experience_years' },
                { data: 'hourly_rate_formatted', name: 'hourly_rate' },
                { data: 'status_badge', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
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
