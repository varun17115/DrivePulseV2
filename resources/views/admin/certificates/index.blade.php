@extends('layouts.admin')

@section('title', 'Certificates')
@section('page_title', 'Certificates')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Certificates</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-award text-primary me-2"></i>Issued Certificates</h6>
        <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-plus me-1"></i> Issue Certificate
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="certificatesTable">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Certificate No</th>
                        <th>Course</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#certificatesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.certificates.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'student', name: 'student.user.name' },
                { data: 'certificate_number', name: 'certificate_number' },
                { data: 'course_name', name: 'course_name' },
                { data: 'grade', name: 'grade' },
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
                    { extend: 'excelHtml5', text: '<i class="fa-solid fa-file-excel me-1"></i> Excel', className: 'btn-sm btn-outline-success mb-2 mb-md-0', exportOptions: { columns: [0,1,2,3,4,5] } },
                    { extend: 'pdfHtml5', text: '<i class="fa-solid fa-file-pdf me-1"></i> PDF', className: 'btn-sm btn-outline-danger mb-2 mb-md-0', exportOptions: { columns: [0,1,2,3,4,5] } },
                    { extend: 'print', text: '<i class="fa-solid fa-print me-1"></i> Print', className: 'btn-sm btn-outline-secondary mb-2 mb-md-0', exportOptions: { columns: [0,1,2,3,4,5] } }
                ]
            }
        });

        $(document).on('click', '.btn-revoke', function() {
            let url = $(this).data('url');
            Swal.fire({
                title: 'Revoke Certificate?',
                text: "This certificate will be marked as invalid.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Revoke'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Revoked!', response.message, 'success');
                                $('#certificatesTable').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
