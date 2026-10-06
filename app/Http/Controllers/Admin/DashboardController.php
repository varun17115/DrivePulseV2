<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Global Stats
        $stats = [
            'total_students' => Student::count(),
            'active_trainers' => Trainer::where('status', '!=', 'inactive')->count(),
            'active_vehicles' => Vehicle::where('status', 'active')->count(),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('paid_at', Carbon::now()->month)
                ->whereYear('paid_at', Carbon::now()->year)
                ->sum('amount')
        ];

        // Recent Bookings (Next upcoming 5)
        $upcomingBookings = Booking::with(['student.user', 'trainer.user', 'vehicle'])
            ->where('booking_date', '>=', Carbon::today())
            ->where('status', 'scheduled')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        // New Enrollments (Last 5)
        $recentStudents = Student::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue chart data (mocked/basic)
        $months = collect(range(1, 12))->map(fn($m) => Carbon::create()->month($m)->format('M'))->toArray();
        $revenueData = array_fill(0, 12, 0); // Initialize with 0s

        $yearlyPayments = Payment::where('status', 'completed')
            ->whereYear('paid_at', Carbon::now()->year)
            ->selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        foreach($yearlyPayments as $month => $data) {
            $revenueData[$month - 1] = (float) $data->total;
        }

        // Vehicle Status Data for Doughnut Chart
        $vehicleStats = [
            'active' => Vehicle::where('status', 'active')->count(),
            'maintenance' => Vehicle::where('status', 'in_service')->count(),
            'inactive' => Vehicle::whereIn('status', ['out_of_order', 'retired'])->count(),
        ];

        return view('admin.dashboard', compact('stats', 'upcomingBookings', 'recentStudents', 'months', 'revenueData', 'vehicleStats'));
    }
}
