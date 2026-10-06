<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $logs = ActivityLog::with('user')->latest();

            if ($request->filled('module')) {
                $logs->where('module', $request->module);
            }

            return DataTables::of($logs)
                ->addColumn('user_info', function ($log) {
                    if ($log->user) {
                        return '<div class="fw-bold text-dark">' . e($log->user->name) . '</div><small class="text-muted">' . e($log->user->email) . '</small>';
                    }
                    return '<span class="badge bg-secondary-subtle text-secondary">System / Guest</span>';
                })
                ->addColumn('module_badge', function ($log) {
                    $colorMap = [
                        'Students' => 'bg-primary-subtle text-primary border-primary-subtle',
                        'Payments' => 'bg-success-subtle text-success border-success-subtle',
                        'Bookings' => 'bg-info-subtle text-info border-info-subtle',
                        'Vehicles' => 'bg-warning-subtle text-warning border-warning-subtle',
                        'Communication' => 'bg-purple-subtle text-purple border-purple-subtle',
                        'Auth' => 'bg-dark-subtle text-dark border-dark-subtle',
                    ];
                    $badgeStyle = $colorMap[$log->module] ?? 'bg-light text-secondary border';
                    return '<span class="badge ' . $badgeStyle . ' border px-3 py-1 rounded-pill">' . e($log->module) . '</span>';
                })
                ->addColumn('action_badge', function ($log) {
                    return '<span class="font-monospace fw-bold text-dark">' . e($log->action) . '</span>';
                })
                ->addColumn('timestamp', function ($log) {
                    return $log->created_at ? $log->created_at->format('M d, Y h:i:s A') : '-';
                })
                ->rawColumns(['user_info', 'module_badge', 'action_badge'])
                ->make(true);
        }

        $totalLogs = ActivityLog::count();
        $modules = ActivityLog::distinct()->pluck('module');

        return view('admin.logs.index', compact('totalLogs', 'modules'));
    }

    public function clear(Request $request)
    {
        ActivityLog::truncate();
        ActivityLog::log('LOGS_PURGED', 'Administrator purged system activity logs', 'General');

        return redirect()->route('admin.logs.index')->with('success', 'System activity logs purged successfully.');
    }

    public function exportCsv()
    {
        $logs = ActivityLog::with('user')->latest()->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=drivepulse_activity_logs_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'User', 'Action', 'Module', 'Description', 'IP Address', 'Timestamp'];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name . " ({$log->user->email})" : 'System',
                    $log->action,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                    $log->created_at ? $log->created_at->toDateTimeString() : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
