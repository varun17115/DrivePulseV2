<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VehicleTimetableController extends Controller
{
    public function index(Request $request)
    {
        $dateString = $request->input('date', Carbon::today()->toDateString());
        try {
            $selectedDate = Carbon::parse($dateString);
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        $prevDate = $selectedDate->copy()->subDay()->toDateString();
        $nextDate = $selectedDate->copy()->addDay()->toDateString();
        $isToday = $selectedDate->isToday();

        $vehicles = Vehicle::where('status', 'active')->orderBy('id')->get();
        $allVehiclesCount = Vehicle::count();

        // 1-hour time slots from 07:00 to 19:00
        $timeSlots = [
            '07:00:00' => '07:00 AM - 08:00 AM',
            '08:00:00' => '08:00 AM - 09:00 AM',
            '09:00:00' => '09:00 AM - 10:00 AM',
            '10:00:00' => '10:00 AM - 11:00 AM',
            '11:00:00' => '11:00 AM - 12:00 PM',
            '12:00:00' => '12:00 PM - 01:00 PM',
            '13:00:00' => '01:00 PM - 02:00 PM',
            '14:00:00' => '02:00 PM - 03:00 PM',
            '15:00:00' => '03:00 PM - 04:00 PM',
            '16:00:00' => '04:00 PM - 05:00 PM',
            '17:00:00' => '05:00 PM - 06:00 PM',
            '18:00:00' => '06:00 PM - 07:00 PM',
        ];

        // Fetch bookings for the selected date
        $bookings = Booking::with(['student.user', 'trainer.user', 'vehicle'])
            ->whereDate('start_time', $selectedDate->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get();

        // Map bookings by vehicle_id and slot
        $timetableGrid = [];
        $totalBookedSlots = 0;

        foreach ($vehicles as $vehicle) {
            $timetableGrid[$vehicle->id] = [];
            foreach ($timeSlots as $slotTime => $slotLabel) {
                $timetableGrid[$vehicle->id][$slotTime] = null;
            }
        }

        foreach ($bookings as $b) {
            if ($b->vehicle_id && isset($timetableGrid[$b->vehicle_id])) {
                $bStartTime = Carbon::parse($b->start_time)->format('H:00:00');
                $timetableGrid[$b->vehicle_id][$bStartTime] = $b;
                $totalBookedSlots++;
            }
        }

        $totalCapacitySlots = count($vehicles) * count($timeSlots);
        $occupancyRate = $totalCapacitySlots > 0 ? round(($totalBookedSlots / $totalCapacitySlots) * 100) : 0;

        $students = Student::with('user')->get();
        $trainers = Trainer::with('user')->whereIn('status', ['available', 'active'])->get();

        return view('admin.vehicles.timetable', compact(
            'vehicles',
            'selectedDate',
            'prevDate',
            'nextDate',
            'isToday',
            'timeSlots',
            'timetableGrid',
            'totalBookedSlots',
            'totalCapacitySlots',
            'occupancyRate',
            'allVehiclesCount',
            'students',
            'trainers'
        ));
    }
}
