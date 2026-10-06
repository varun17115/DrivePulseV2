@extends('layouts.admin')

@section('title', 'Vehicle Details - ' . $vehicle->registration_number)
@section('page_title', 'Vehicle Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.vehicles.index') }}">Vehicles</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $vehicle->registration_number }}</li>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 mb-4">
            <div class="card-body p-4 text-center">
                <div class="avatar bg-primary-subtle text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fa-solid fa-car-side"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</h5>
                <p class="text-muted fs-8 mb-2">{{ $vehicle->registration_number }}</p>

                @php
                    $badges = [
                        'active' => 'bg-success',
                        'in_service' => 'bg-warning',
                        'out_of_order' => 'bg-danger',
                        'retired' => 'bg-secondary',
                    ];
                    $badge = $badges[$vehicle->status] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badge }} mb-3 px-3 py-2 rounded-pill">{{ ucwords(str_replace('_', ' ', $vehicle->status)) }}</span>

                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Vehicle
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-gauge-high text-primary me-2"></i>Specifications</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-code-branch me-2"></i>Transmission</span>
                        <span class="fw-semibold text-dark text-capitalize">{{ $vehicle->transmission_type }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-gas-pump me-2"></i>Fuel Type</span>
                        <span class="fw-semibold text-dark text-capitalize">{{ $vehicle->fuel_type }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fa-solid fa-road me-2"></i>Odometer</span>
                        <span class="fw-semibold text-dark">{{ number_format($vehicle->current_odometer) }} km</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-file-shield text-primary me-2"></i>Compliance Tracker</h6>
            </div>
            <div class="card-body">
                @php
                    $today = \Carbon\Carbon::today();
                @endphp
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3 fs-8">
                    <!-- Insurance -->
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Insurance</span>
                        @if($vehicle->insurance_expiry)
                            @if($vehicle->insurance_expiry->isPast())
                                <span class="badge bg-danger rounded-pill px-2">Expired {{ $vehicle->insurance_expiry->format('d M y') }}</span>
                            @elseif($vehicle->insurance_expiry->diffInDays($today) <= 30)
                                <span class="badge bg-warning text-dark rounded-pill px-2">Due {{ $vehicle->insurance_expiry->format('d M y') }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Valid {{ $vehicle->insurance_expiry->format('d M y') }}</span>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </li>

                    <!-- Fitness -->
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Fitness Cert</span>
                        @if($vehicle->fitness_certificate_expiry)
                            @if($vehicle->fitness_certificate_expiry->isPast())
                                <span class="badge bg-danger rounded-pill px-2">Expired {{ $vehicle->fitness_certificate_expiry->format('d M y') }}</span>
                            @elseif($vehicle->fitness_certificate_expiry->diffInDays($today) <= 30)
                                <span class="badge bg-warning text-dark rounded-pill px-2">Due {{ $vehicle->fitness_certificate_expiry->format('d M y') }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Valid {{ $vehicle->fitness_certificate_expiry->format('d M y') }}</span>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </li>

                    <!-- Pollution -->
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Pollution Check</span>
                        @if($vehicle->pollution_check_expiry)
                            @if($vehicle->pollution_check_expiry->isPast())
                                <span class="badge bg-danger rounded-pill px-2">Expired {{ $vehicle->pollution_check_expiry->format('d M y') }}</span>
                            @elseif($vehicle->pollution_check_expiry->diffInDays($today) <= 30)
                                <span class="badge bg-warning text-dark rounded-pill px-2">Due {{ $vehicle->pollution_check_expiry->format('d M y') }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill px-2">Valid {{ $vehicle->pollution_check_expiry->format('d M y') }}</span>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" id="vehicleTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">
                            <i class="fa-solid fa-wrench me-1"></i> Maintenance Log
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                            <i class="fa-solid fa-calendar-check me-1"></i> Recent Bookings
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <!-- Maintenance Tab -->
                    <div class="tab-pane fade show active" id="maintenance" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Service History</h6>
                            <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                                <i class="fa-solid fa-plus me-1"></i> Add Record
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Odometer</th>
                                        <th>Cost</th>
                                        <th>Next Due</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicle->services as $service)
                                    <tr>
                                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($service->service_date)->format('d M Y') }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $service->service_type }}</span>
                                            @if($service->service_center)
                                                <div class="fs-8 text-muted">{{ $service->service_center }}</div>
                                            @endif
                                        </td>
                                        <td>{{ number_format($service->odometer_reading) }} km</td>
                                        <td class="fw-semibold text-danger">₹{{ number_format($service->cost, 2) }}</td>
                                        <td>
                                            @if($service->next_service_due)
                                                {{ \Carbon\Carbon::parse($service->next_service_due)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit-service"
                                                    data-id="{{ $service->id }}"
                                                    data-type="{{ $service->service_type }}"
                                                    data-date="{{ \Carbon\Carbon::parse($service->service_date)->format('Y-m-d') }}"
                                                    data-cost="{{ $service->cost }}"
                                                    data-odometer="{{ $service->odometer_reading }}"
                                                    data-due="{{ $service->next_service_due ? \Carbon\Carbon::parse($service->next_service_due)->format('Y-m-d') : '' }}"
                                                    data-center="{{ $service->service_center }}"
                                                    data-description="{{ $service->description }}"
                                                    data-action="{{ route('admin.vehicles.services.update', $service->id) }}"
                                                    title="Edit Record">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <form action="{{ route('admin.vehicles.services.destroy', $service->id) }}" method="POST" class="d-inline delete-service-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-service" title="Delete Record">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No maintenance records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bookings Tab -->
                    <div class="tab-pane fade" id="bookings" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Student</th>
                                        <th>Instructor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicle->bookings->take(20) as $booking)
                                    <tr>
                                        <td class="fw-semibold">{{ $booking->booking_date->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                                        <td>{{ $booking->student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->trainer->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'info') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No booking records found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Log Maintenance Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.vehicles.services.store', $vehicle->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                        <input type="text" name="service_type" class="form-control" placeholder="e.g. Regular Oil Change, Tire Replacement" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="service_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Cost (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="cost" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Odometer Reading <span class="text-danger">*</span></label>
                            <input type="number" name="odometer_reading" class="form-control" value="{{ $vehicle->current_odometer }}" required>
                            <div class="form-text">Will update vehicle's current odometer if higher.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Next Service Due (Date)</label>
                            <input type="date" name="next_service_due" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Center</label>
                        <input type="text" name="service_center" class="form-control" placeholder="e.g. Joe's Auto Repair">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Notes</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Details about what was fixed or checked..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Maintenance Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServiceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                        <input type="text" name="service_type" id="edit_service_type" class="form-control" placeholder="e.g. Regular Oil Change, Tire Replacement" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="service_date" id="edit_service_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Cost (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="cost" id="edit_cost" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Odometer Reading <span class="text-danger">*</span></label>
                            <input type="number" name="odometer_reading" id="edit_odometer_reading" class="form-control" required>
                            <div class="form-text">Will update vehicle's current odometer if higher.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Next Service Due (Date)</label>
                            <input type="date" name="next_service_due" id="edit_next_service_due" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Center</label>
                        <input type="text" name="service_center" id="edit_service_center" class="form-control" placeholder="e.g. Joe's Auto Repair">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Notes</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3" placeholder="Details about what was fixed or checked..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle Edit Service Modal population
        $('.btn-edit-service').on('click', function() {
            let button = $(this);
            let action = button.data('action');

            $('#editServiceForm').attr('action', action);
            $('#edit_service_type').val(button.data('type'));
            $('#edit_service_date').val(button.data('date'));
            $('#edit_cost').val(button.data('cost'));
            $('#edit_odometer_reading').val(button.data('odometer'));
            $('#edit_next_service_due').val(button.data('due'));
            $('#edit_service_center').val(button.data('center'));
            $('#edit_description').val(button.data('description'));

            let editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));
            editModal.show();
        });

        // Handle Delete Service Confirmation
        $('.btn-delete-service').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

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
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
