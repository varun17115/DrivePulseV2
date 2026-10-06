@extends('layouts.admin')

@section('title', 'Fleet & Vehicles')
@section('page_title', 'Vehicle Management')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicles</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-car-side text-primary me-2"></i>Fleet Overview</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vehicles.timetable') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-table-cells me-1"></i> Timetable Matrix
            </a>
            <a href="{{ route('admin.vehicles.export') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel/CSV
            </a>
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Add Vehicle
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="vehiclesTable">
                <thead class="bg-light">
                    <tr>
                        <th>Plate / Reg #</th>
                        <th>Vehicle</th>
                        <th>Transmission</th>
                        <th>Fuel</th>
                        <th>Odometer</th>
                        <th>Compliance</th>
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
        $('#vehiclesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.vehicles.index') }}",
            columns: [
                { data: 'registration_number', name: 'registration_number', className: 'fw-bold text-primary' },
                { data: 'vehicle_info', name: 'make' },
                { data: 'transmission_badge', name: 'transmission_type' },
                { data: 'fuel_badge', name: 'fuel_type' },
                { data: 'odometer_formatted', name: 'current_odometer' },
                { data: 'compliance_status', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[0, 'asc']],
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
