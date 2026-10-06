<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $trainer = Auth::user()->trainer;
        if (!$trainer) abort(403, 'Trainer profile not found.');

        // Get vehicles that this trainer has used or all active fleet vehicles
        $vehicles = Vehicle::withCount(['bookings' => function($q) use ($trainer) {
            $q->where('trainer_id', $trainer->id);
        }])->paginate(10);

        return view('trainer.vehicles.index', compact('vehicles'));
    }
}
