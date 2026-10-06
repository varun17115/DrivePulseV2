@extends('layouts.admin')

@section('title', 'Email Broadcast & Offers')
@section('page_title', 'Promotional Email Broadcast & Campaign Manager')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Email Broadcast</li>
@endsection

@section('content')
<!-- KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary bg-gradient text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Total Emails Dispatched</div>
                    <h2 class="fw-bold mb-0 font-monospace">{{ $totalSent }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-paper-plane"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success bg-gradient text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Total Active Students</div>
                    <h2 class="fw-bold mb-0 font-monospace">{{ $totalStudents }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning bg-gradient text-dark">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-dark-50 text-2xs text-uppercase fw-bold mb-1">Students with Due Fees</div>
                    <h2 class="fw-bold mb-0 font-monospace">{{ $dueStudentsCount }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-danger bg-gradient text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 text-2xs text-uppercase fw-bold mb-1">Delivery Failures</div>
                    <h2 class="fw-bold mb-0 font-monospace">{{ $totalFailed }}</h2>
                </div>
                <div class="fs-1 opacity-50"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Compose Campaign Form -->
    <div class="col-12 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-bullhorn text-primary me-2"></i>Compose Email Broadcast</h5>
                <p class="text-muted small mb-0">Dispatch special offers, announcements, and notices</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.broadcast.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Target Recipient Group <span class="text-danger">*</span></label>
                        <select name="recipient_group" id="recipient_group" class="form-select rounded-3 shadow-none @error('recipient_group') is-invalid @enderror" required>
                            <option value="all_students">All Students ({{ $totalStudents }})</option>
                            <option value="active_students">Active Enrolled Students</option>
                            <option value="due_students">Students with Due Fees Only ({{ $dueStudentsCount }})</option>
                            <option value="trainers">All Instructors / Trainers</option>
                            <option value="all_users">All System Users</option>
                            <option value="custom">Custom Email Addresses List</option>
                        </select>
                        @error('recipient_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 d-none" id="custom_emails_container">
                        <label class="form-label fw-bold text-dark">Comma-Separated Emails</label>
                        <input type="text" name="custom_emails" class="form-control rounded-3 shadow-none" placeholder="student1@gmail.com, student2@gmail.com">
                        <small class="text-muted">Enter multiple email addresses separated by commas</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Email Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control rounded-3 shadow-none @error('subject') is-invalid @enderror" placeholder="e.g. Special Holiday Driving Course Discount - 20% Off!" required>
                        @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Email Message Body <span class="text-danger">*</span></label>
                        <textarea name="message" rows="6" class="form-control rounded-3 shadow-none @error('message') is-invalid @enderror" placeholder="Dear Student, we are excited to announce our upcoming defensive driving masterclass..." required></textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Promotional Banner / Poster (Optional)</label>
                        <input type="file" name="offer_banner" class="form-control rounded-3 shadow-none" accept="image/*">
                        <small class="text-muted">PNG, JPG or WebP up to 3MB</small>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="send_in_app" id="send_in_app" value="1" checked>
                        <label class="form-check-label fw-semibold text-dark" for="send_in_app">
                            Also generate In-App Dashboard Notifications for recipients
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-paper-plane me-2"></i> Launch Broadcast Campaign
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Logs History Table -->
    <div class="col-12 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Broadcast Delivery History</h5>
                    <p class="text-muted small mb-0">Audit logs of all dispatched promotional and system emails</p>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100" id="emailLogsTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Recipient</th>
                                <th>Subject</th>
                                <th>Snippet</th>
                                <th>Status</th>
                                <th>Sent Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Preview Modal -->
<div class="modal fade" id="emailViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-primary text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-envelope-open-text me-2"></i>Broadcast Email Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="text-muted small">To:</div>
                    <div class="fw-bold text-dark fs-6" id="modalRecipient"></div>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="text-muted small">Subject:</div>
                    <h5 class="fw-bold text-primary mb-0" id="modalSubject"></h5>
                </div>
                <div class="mb-3">
                    <div class="text-muted small mb-2">Message Content:</div>
                    <div class="p-3 bg-light rounded-3 border" id="modalBody" style="min-height: 150px;"></div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#recipient_group').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom_emails_container').removeClass('d-none');
        } else {
            $('#custom_emails_container').addClass('d-none');
        }
    });

    const table = $('#emailLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.broadcast.index') }}",
        columns: [
            { data: 'recipient_email', name: 'recipient_email', className: 'font-monospace small fw-bold' },
            { data: 'subject', name: 'subject', className: 'fw-semibold text-dark' },
            { data: 'snippet', name: 'body', orderable: false },
            { data: 'status_badge', name: 'status', orderable: false },
            { data: 'sent_time', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ],
        order: [[4, 'desc']],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search email logs..."
        }
    });

    const emailModal = new bootstrap.Modal(document.getElementById('emailViewModal'));

    $(document).on('click', '.btn-view-email', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/admin/broadcast/${id}`,
            type: 'GET',
            success: function(data) {
                $('#modalRecipient').text(data.recipient_email);
                $('#modalSubject').text(data.subject);
                $('#modalBody').html(data.body);
                emailModal.show();
            }
        });
    });
});
</script>
@endpush
