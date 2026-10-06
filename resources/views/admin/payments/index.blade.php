@extends('layouts.admin')

@section('title', 'Payments & Invoicing')
@section('page_title', 'Payments & Invoicing')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Payments</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Record New Payment</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.payments.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Student <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select select2" required>
                            <option value="">Choose Student...</option>
                            @foreach(\App\Models\Student::with('user')->get() as $student)
                                <option value="{{ $student->id }}">{{ $student->user->name ?? 'N/A' }} (Due: ₹{{ number_format($student->due_amount, 2) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="card">Credit/Debit Card</option>
                            <option value="upi">UPI / Online</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Transaction ID / Reference</label>
                        <input type="text" name="transaction_id" class="form-control" placeholder="Optional">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional remarks..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Record Payment</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-receipt text-primary me-2"></i>Payment History</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.due-payments.index') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-hand-holding-dollar me-1"></i> Due Ledger
                    </a>
                    <a href="{{ route('admin.payments.export') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-file-excel me-1"></i> Export Excel/CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100" id="paymentsTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.payments.index') }}",
            columns: [
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'student', name: 'student.user.name' },
                { data: 'amount', name: 'amount' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'status_badge', name: 'status' },
                { data: 'paid_at', name: 'paid_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[5, 'desc']],
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
