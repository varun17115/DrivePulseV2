@extends('layouts.admin')

@section('title', 'System Activity Logs')
@section('page_title', 'System Audit & Activity Logs')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
@endsection

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-clipboard-list text-primary me-2"></i>Audit Trail & Event History</h5>
            <p class="text-muted small mb-0">Record of administrative actions, financial transactions, and user logins</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select id="filter_module" class="form-select form-select-sm rounded-pill shadow-none" style="width: 170px;">
                <option value="">All Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}">{{ $mod }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.logs.export') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-file-excel me-1"></i> Export CSV
            </a>
            <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all activity logs?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                    <i class="fa-solid fa-trash-can me-1"></i> Purge Logs
                </button>
            </form>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="activityLogsTable">
                <thead class="bg-light">
                    <tr>
                        <th>User</th>
                        <th>Module</th>
                        <th>Action Event</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
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
    const table = $('#activityLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.logs.index') }}",
            data: function(d) {
                d.module = $('#filter_module').val();
            }
        },
        columns: [
            { data: 'user_info', name: 'user.name' },
            { data: 'module_badge', name: 'module' },
            { data: 'action_badge', name: 'action' },
            { data: 'description', name: 'description', className: 'small text-secondary' },
            { data: 'ip_address', name: 'ip_address', className: 'font-monospace small text-muted' },
            { data: 'timestamp', name: 'created_at' }
        ],
        order: [[5, 'desc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search audit logs..."
        }
    });

    $('#filter_module').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endpush
