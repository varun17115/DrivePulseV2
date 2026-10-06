<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\VehicleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vehicles = Vehicle::query();

            return DataTables::of($vehicles)
                ->addColumn('vehicle_info', function ($vehicle) {
                    return '<div class="fw-bold text-dark">' . e($vehicle->make) . ' ' . e($vehicle->model) . '</div>' .
                           '<span class="text-muted fs-8">' . e($vehicle->year) . '</span>';
                })
                ->addColumn('transmission_badge', function ($vehicle) {
                    return $vehicle->transmission_type === 'automatic'
                        ? '<span class="badge bg-info-subtle text-info border border-info-subtle"><i class="fa-solid fa-gear me-1"></i>Automatic</span>'
                        : '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="fa-solid fa-code-branch me-1"></i>Manual</span>';
                })
                ->addColumn('fuel_badge', function ($vehicle) {
                    $fuelIcons = [
                        'petrol' => 'fa-gas-pump text-primary',
                        'diesel' => 'fa-oil-can text-warning',
                        'electric' => 'fa-bolt text-success',
                        'hybrid' => 'fa-leaf text-info',
                    ];
                    $icon = $fuelIcons[$vehicle->fuel_type] ?? 'fa-gas-pump text-primary';
                    return '<span class="text-capitalize"><i class="fa-solid ' . $icon . ' me-1"></i>' . e($vehicle->fuel_type) . '</span>';
                })
                ->addColumn('odometer_formatted', function ($vehicle) {
                    return number_format($vehicle->current_odometer) . ' km';
                })
                ->addColumn('status_badge', function ($vehicle) {
                    $badges = [
                        'active' => 'bg-success',
                        'in_service' => 'bg-warning',
                        'out_of_order' => 'bg-danger',
                        'retired' => 'bg-secondary',
                    ];
                    $badgeClass = $badges[$vehicle->status] ?? 'bg-secondary';
                    $statusLabel = ucwords(str_replace('_', ' ', $vehicle->status));
                    return '<span class="badge ' . $badgeClass . '">' . $statusLabel . '</span>';
                })
                ->addColumn('compliance_status', function ($vehicle) {
                    $today = Carbon::today();
                    $alerts = [];

                    if ($vehicle->insurance_expiry) {
                        if ($vehicle->insurance_expiry->isPast()) {
                            $alerts[] = '<span class="badge bg-danger" title="Insurance Expired"><i class="fa-solid fa-shield-halved me-1"></i>Insurance Expired</span>';
                        } elseif ($vehicle->insurance_expiry->diffInDays($today) <= 30) {
                            $alerts[] = '<span class="badge bg-warning text-dark" title="Insurance Expiring Soon"><i class="fa-solid fa-shield-halved me-1"></i>Insurance Expiring</span>';
                        }
                    }

                    if ($vehicle->fitness_certificate_expiry) {
                        if ($vehicle->fitness_certificate_expiry->isPast()) {
                            $alerts[] = '<span class="badge bg-danger" title="Fitness Expired"><i class="fa-solid fa-file-contract me-1"></i>Fitness Expired</span>';
                        } elseif ($vehicle->fitness_certificate_expiry->diffInDays($today) <= 30) {
                            $alerts[] = '<span class="badge bg-warning text-dark" title="Fitness Expiring Soon"><i class="fa-solid fa-file-contract me-1"></i>Fitness Expiring</span>';
                        }
                    }

                    if ($vehicle->pollution_check_expiry) {
                        if ($vehicle->pollution_check_expiry->isPast()) {
                            $alerts[] = '<span class="badge bg-danger" title="Pollution Check Expired"><i class="fa-solid fa-smog me-1"></i>Pollution Expired</span>';
                        } elseif ($vehicle->pollution_check_expiry->diffInDays($today) <= 30) {
                            $alerts[] = '<span class="badge bg-warning text-dark" title="Pollution Check Expiring Soon"><i class="fa-solid fa-smog me-1"></i>Pollution Expiring</span>';
                        }
                    }

                    if (empty($alerts)) {
                        return '<span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check me-1"></i>Compliant</span>';
                    }

                    return implode(' ', $alerts);
                })
                ->addColumn('actions', function ($vehicle) {
                    $showUrl = route('admin.vehicles.show', $vehicle->id);
                    $editUrl = route('admin.vehicles.edit', $vehicle->id);
                    $deleteUrl = route('admin.vehicles.destroy', $vehicle->id);

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $showUrl . '" class="btn btn-sm btn-outline-info" title="View"><i class="fa-solid fa-eye"></i></a>
                            <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-url="' . $deleteUrl . '" data-name="' . e($vehicle->registration_number) . '" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['vehicle_info', 'transmission_badge', 'fuel_badge', 'status_badge', 'compliance_status', 'actions'])
                ->make(true);
        }

        return view('admin.vehicles.index');
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(StoreVehicleRequest $request)
    {
        try {
            Vehicle::create($request->validated());
            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle added to fleet successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to add vehicle: ' . $e->getMessage());
        }
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['services' => function ($q) {
            $q->orderBy('service_date', 'desc');
        }, 'bookings.student.user', 'bookings.trainer.user']);

        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        try {
            $vehicle->update($request->validated());
            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update vehicle: ' . $e->getMessage());
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        try {
            $vehicle->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Vehicle removed from fleet successfully!']);
            }

            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle removed from fleet successfully!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete vehicle: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }

    public function addService(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'service_type' => 'required|string|max:100',
            'service_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'odometer_reading' => 'required|integer|min:0',
            'service_center' => 'nullable|string|max:150',
            'next_service_due' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $vehicle->services()->create($validated);

        if ($validated['odometer_reading'] > $vehicle->current_odometer) {
            $vehicle->update(['current_odometer' => $validated['odometer_reading']]);
        }

        \App\Models\ActivityLog::log('VEHICLE_SERVICE_ADDED', "Added {$validated['service_type']} record for {$vehicle->registration_number}", 'Vehicles');

        return redirect()->route('admin.vehicles.show', $vehicle->id)->with('success', 'Maintenance record added successfully!');
    }

    public function exportCsv()
    {
        $vehicles = Vehicle::all();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivepulse_vehicles_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Registration No', 'Make', 'Model', 'Year', 'Transmission', 'Fuel Type', 'Odometer (km)', 'Insurance Expiry', 'Status'];

        $callback = function () use ($vehicles, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($vehicles as $v) {
                fputcsv($file, [
                    $v->registration_number,
                    $v->make,
                    $v->model,
                    $v->year,
                    ucfirst($v->transmission_type),
                    ucfirst($v->fuel_type),
                    $v->current_odometer,
                    $v->insurance_expiry ? $v->insurance_expiry->format('Y-m-d') : '',
                    $v->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
