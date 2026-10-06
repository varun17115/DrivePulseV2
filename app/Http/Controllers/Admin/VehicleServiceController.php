<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VehicleServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = VehicleService::with('vehicle')->select('vehicle_services.*');
            return DataTables::of($services)
                ->addColumn('vehicle_name', fn($s) => $s->vehicle ? e($s->vehicle->make) . ' ' . e($s->vehicle->model) . ' (' . e($s->vehicle->registration_number) . ')' : 'N/A')
                ->addColumn('formatted_cost', fn($s) => '₹' . number_format($s->cost, 2))
                ->addColumn('formatted_date', fn($s) => \Carbon\Carbon::parse($s->service_date)->format('d M Y'))
                ->addColumn('next_due_formatted', fn($s) => $s->next_service_due ? \Carbon\Carbon::parse($s->next_service_due)->format('d M Y') : '<span class="text-muted">N/A</span>')
                ->addColumn('actions', function ($service) {
                    $editUrl = route('admin.vehicles.services.update', $service->id);
                    $deleteUrl = route('admin.vehicles.services.destroy', $service->id);
                    $vehicleId = $service->vehicle_id;
                    $viewVehicleUrl = route('admin.vehicles.show', $vehicleId);

                    return '
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="' . $viewVehicleUrl . '" class="btn btn-sm btn-outline-info" title="View Vehicle"><i class="fa-solid fa-car"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-service" data-url="' . $deleteUrl . '" title="Delete Service">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['next_due_formatted', 'actions'])
                ->make(true);
        }

        $vehicles = Vehicle::orderBy('make')->get();
        return view('admin.vehicle-services.index', compact('vehicles'));
    }

    public function update(Request $request, VehicleService $service)
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

        $service->update($validated);

        // Optional: Update vehicle current odometer if needed
        $vehicle = $service->vehicle;
        if ($validated['odometer_reading'] > $vehicle->current_odometer) {
            $vehicle->update(['current_odometer' => $validated['odometer_reading']]);
        }

        return back()->with('success', 'Maintenance record updated successfully!');
    }

    public function destroy(VehicleService $service)
    {
        $vehicleId = $service->vehicle_id;
        $service->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Maintenance record deleted successfully!']);
        }

        return back()->with('success', 'Maintenance record deleted successfully!');
    }
}
