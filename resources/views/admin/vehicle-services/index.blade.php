@extends('layouts.admin')

@section('title', 'Fleet Maintenance')
@section('page_title', 'Maintenance Records')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Fleet Maintenance</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-wrench text-primary me-2"></i>Maintenance Overview</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="servicesTable">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Type</th>
                        <th>Odometer</th>
                        <th>Cost</th>
                        <th>Next Due</th>
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
        $('#servicesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.vehicle-services.index') }}",
            columns: [
                { data: 'formatted_date', name: 'service_date' },
                { data: 'vehicle_name', name: 'vehicle_id', orderable: false },
                { data: 'service_type', name: 'service_type' },
                { data: 'odometer_reading', name: 'odometer_reading' },
                { data: 'formatted_cost', name: 'cost' },
                { data: 'next_due_formatted', name: 'next_service_due', orderable: false },
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

        // Handle Delete Service Confirmation
        $(document).on('click', '.btn-delete-service', function(e) {
            e.preventDefault();
            let url = $(this).data('url');

            Swal.fire({
                title: 'Delete Maintenance Record?',
                text: 'Are you sure you want to remove this service log? This action cannot be undone.',
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
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#servicesTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
