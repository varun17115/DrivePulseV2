@extends('layouts.admin')

@section('title', 'Students Directory')
@section('page_title', 'Students Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Students</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-user-graduate text-primary me-2"></i>All Enrolled Students</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.students.export') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel/CSV
            </a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Enroll New Student
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="studentsTable">
                <thead class="bg-light">
                    <tr>
                        <th>Admission #</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>License Type</th>
                        <th>Status</th>
                        <th>Financials</th>
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
        $('#studentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.students.index') }}",
            columns: [
                { data: 'admission_number', name: 'admission_number', className: 'fw-semibold text-primary' },
                { data: 'name', name: 'user.name' },
                { data: 'email', name: 'user.email' },
                { data: 'phone', name: 'user.phone' },
                { data: 'license_type', name: 'license_type' },
                { data: 'course_status_badge', name: 'course_status' },
                { data: 'financials', name: 'financials', orderable: false, searchable: false },
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
