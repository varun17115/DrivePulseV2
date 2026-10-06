@extends('layouts.admin')

@section('title', 'My Payments & Invoices')
@section('page_title', 'Fee & Payment History')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Payments</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="text-muted small fw-semibold mb-1">Total Course Fee</div>
            <h3 class="mb-0 fw-bold text-dark">₹{{ number_format($student->total_amount, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-success">
            <div class="text-success small fw-semibold mb-1">Total Paid</div>
            <h3 class="mb-0 fw-bold">₹{{ number_format($student->paid_amount, 2) }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 {{ $student->due_amount > 0 ? 'text-danger' : 'text-primary' }}">
            <div class="small fw-semibold mb-1">Amount Due</div>
            <h3 class="mb-0 fw-bold">₹{{ number_format($student->due_amount, 2) }}</h3>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Invoice History</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Invoice No#</th>
                        <th>Amount</th>
                        <th>Method / Ref</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td class="ps-4">{{ $payment->created_at->format('d M, Y') }}</td>
                            <td class="font-monospace fw-semibold">{{ $payment->invoice_number }}</td>
                            <td class="fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <div>{{ ucfirst($payment->payment_method) }}</div>
                                <div class="small text-muted text-monospace">{{ $payment->transaction_id ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $payment->status === 'paid' ? 'bg-success' : ($payment->status === 'failed' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($payment->status === 'paid')
                                    <a href="{{ route('student.payments.print', $payment->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" target="_blank">
                                        <i class="fa-solid fa-download me-1"></i> Print
                                    </a>
                                @else
                                    <span class="text-muted small">Not Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0">No payment history found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
