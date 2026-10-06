@extends('layouts.admin')

@section('title', 'Student Mock Tests')
@section('page_title', 'Mock Tests Evaluation')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-questions.index') }}">Question Bank</a></li>
    <li class="breadcrumb-item active" aria-current="page">Student Tests</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-file-signature text-primary me-2"></i>Student Mock Test Attempts</h6>
        <div>
            <a href="{{ route('admin.mock-questions.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-layer-group me-1"></i> Question Bank
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="mockTestsTable">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Score</th>
                        <th>Result</th>
                        <th>Duration</th>
                        <th>Date</th>
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
        $('#mockTestsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.mock-tests.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'student', name: 'student.user.name' },
                { data: 'score_formatted', name: 'score' },
                { data: 'result_badge', name: 'result' },
                { data: 'duration', name: 'time_taken_seconds' },
                { data: 'date', name: 'created_at' },
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
    });
</script>
@endpush
