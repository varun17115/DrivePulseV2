@extends('layouts.admin')

@section('title', 'Mock Test Questions')
@section('page_title', 'Question Bank')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Question Bank</li>
@endsection

@section('content')
<div class="card border-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-layer-group text-primary me-2"></i>Question Bank</h6>
        <div>
            <a href="{{ route('admin.mock-tests.index') }}" class="btn btn-outline-info btn-sm rounded-pill px-3 me-2">
                <i class="fa-solid fa-file-signature me-1"></i> Student Tests
            </a>
            <a href="{{ route('admin.mock-questions.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Add Question
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="questionsTable">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th style="min-width: 300px;">Question</th>
                        <th>Correct Answer</th>
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
        $('#questionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.mock-questions.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'category_badge', name: 'category' },
                { data: 'question_snippet', name: 'question' },
                { data: 'correct', name: 'correct_option' },
                { data: 'status', name: 'is_active' },
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
                    { extend: 'excelHtml5', text: '<i class="fa-solid fa-file-excel me-1"></i> Excel', className: 'btn-sm btn-outline-success mb-2 mb-md-0', exportOptions: { columns: [0,1,2,3,4] } },
                    { extend: 'pdfHtml5', text: '<i class="fa-solid fa-file-pdf me-1"></i> PDF', className: 'btn-sm btn-outline-danger mb-2 mb-md-0', exportOptions: { columns: [0,1,2,3,4] } },
                    { extend: 'print', text: '<i class="fa-solid fa-print me-1"></i> Print', className: 'btn-sm btn-outline-secondary mb-2 mb-md-0', exportOptions: { columns: [0,1,2,3,4] } }
                ]
            }
        });

        $(document).on('click', '.btn-delete', function() {
            let url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this question.",
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
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#questionsTable').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
