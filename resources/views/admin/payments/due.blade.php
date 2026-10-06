@extends('layouts.admin')

@section('title', 'Due Payments & Installments')
@section('page_title', 'Due Payments & Installment Balance Manager')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
    <li class="breadcrumb-item active" aria-current="page">Due Ledger</li>
@endsection

@section('content')
<!-- KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-danger bg-gradient text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Total Outstanding Dues</div>
                    <h2 class="fw-bold mb-0 font-monospace">₹{{ number_format($totalDueAmount, 2) }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning bg-gradient text-dark">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-dark-50 text-2xs text-uppercase fw-bold mb-1">Students with Dues</div>
                    <h2 class="fw-bold mb-0 font-monospace">{{ $dueStudentsCount }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success bg-gradient text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Total Fees Collected</div>
                    <h2 class="fw-bold mb-0 font-monospace">₹{{ number_format($totalPaidAmount, 2) }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Highest Single Due</div>
                    <h2 class="fw-bold mb-0 font-monospace text-warning">₹{{ number_format($highestDue, 2) }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-receipt"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Pending Fee Balances Ledger</h5>
            <p class="text-muted small mb-0">List of enrolled students with outstanding fee installments</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.due-payments.export') }}" class="btn btn-outline-success rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel/CSV
            </a>
            <form action="{{ route('admin.due-payments.bulk-remind') }}" method="POST" onsubmit="return confirm('Send due payment reminder alerts to all {{ $dueStudentsCount }} students?');">
                @csrf
                <button type="submit" class="btn btn-warning rounded-pill px-3 fw-bold">
                    <i class="fa-solid fa-bullhorn me-1"></i> Send Bulk Reminders
                </button>
            </form>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="duePaymentsTable">
                <thead class="bg-light">
                    <tr>
                        <th>Student Details</th>
                        <th>Course Package</th>
                        <th>Total Fee</th>
                        <th>Amount Paid</th>
                        <th>Pending Due</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Record Installment Payment Modal -->
<div class="modal fade" id="installmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-success text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-money-bill-wave me-2"></i>Record Installment Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="installmentForm">
                @csrf
                <input type="hidden" name="student_id" id="modal_student_id">

                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-3 border mb-3">
                        <div class="fw-bold text-dark fs-6" id="modal_student_name">Student Name</div>
                        <div class="small text-muted font-monospace" id="modal_student_adm">Admission No</div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <span class="text-muted small">Current Pending Due:</span>
                            <span class="fs-5 fw-bold text-danger font-monospace" id="modal_student_due">₹0.00</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Installment Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="modal_amount" class="form-control form-control-lg rounded-3 font-monospace fw-bold" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select rounded-3 shadow-none" required>
                            <option value="cash">Cash</option>
                            <option value="card">Credit / Debit Card</option>
                            <option value="upi">UPI / Instant Transfer</option>
                            <option value="bank_transfer">Bank Wire Transfer</option>
                            <option value="online">Online Gateway</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Transaction / Reference ID</label>
                        <input type="text" name="transaction_id" class="form-control rounded-3 shadow-none" placeholder="e.g. TXN-998823">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" rows="2" class="form-control rounded-3 shadow-none" placeholder="Partial payment installment installment note..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm" id="btnSubmitInstallment">
                        Confirm & Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#duePaymentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.due-payments.index') }}",
        columns: [
            { data: 'student_name', name: 'user.name' },
            { data: 'course_badge', name: 'license_type' },
            { data: 'total_fee', name: 'total_amount' },
            { data: 'paid_fee', name: 'paid_amount' },
            { data: 'pending_due', name: 'due_amount' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ],
        order: [[4, 'desc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search due customers..."
        }
    });

    const installModal = new bootstrap.Modal(document.getElementById('installmentModal'));

    $(document).on('click', '.btn-pay-installment', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const adm = $(this).data('adm');
        const due = parseFloat($(this).data('due'));

        $('#modal_student_id').val(id);
        $('#modal_student_name').text(name);
        $('#modal_student_adm').text(`Admission No: ${adm}`);
        $('#modal_student_due').text(`₹${due.toFixed(2)}`);
        $('#modal_amount').val(due.toFixed(2)).attr('max', due);

        installModal.show();
    });

    $('#installmentForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btnSubmitInstallment');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

        $.ajax({
            url: "{{ route('admin.due-payments.record-installment') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                installModal.hide();
                Swal.fire({
                    title: 'Payment Recorded!',
                    text: res.message,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Print Receipt',
                    cancelButtonText: 'Done',
                    confirmButtonColor: '#10b981'
                }).then((result) => {
                    if (result.isConfirmed && res.invoice_url) {
                        window.open(res.invoice_url, '_blank');
                    }
                    table.ajax.reload();
                });
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Failed to record installment payment.';
                Swal.fire('Error', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Confirm & Record Payment');
            }
        });
    });

    $(document).on('click', '.btn-send-reminder', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const due = $(this).data('due');

        Swal.fire({
            title: `Send Reminder?`,
            text: `Send outstanding fee reminder for ₹${parseFloat(due).toFixed(2)} to ${name}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Yes, Send Reminder'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/due-payments/${id}/remind`,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.fire('Reminder Sent', res.message, 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'Could not send reminder at this time.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
