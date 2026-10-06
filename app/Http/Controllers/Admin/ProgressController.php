<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Progress;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $progresses = Progress::with(['student.user', 'trainer.user', 'booking'])
                ->select('progress.*');

            return DataTables::of($progresses)
                ->addColumn('date', function ($progress) {
                    if ($progress->booking && $progress->booking->booking_date) {
                        return $progress->booking->booking_date->format('d M Y');
                    }
                    return $progress->created_at->format('d M Y');
                })
                ->addColumn('student', function ($progress) {
                    return $progress->student && $progress->student->user 
                        ? e($progress->student->user->name) 
                        : 'N/A';
                })
                ->addColumn('trainer', function ($progress) {
                    return $progress->trainer && $progress->trainer->user 
                        ? e($progress->trainer->user->name) 
                        : 'N/A';
                })
                ->addColumn('skill_topic', function ($progress) {
                    return e($progress->skill_topic);
                })
                ->addColumn('competency_badge', function ($progress) {
                    $badges = [
                        'needs_practice' => 'bg-warning text-dark',
                        'proficient' => 'bg-info text-dark',
                        'mastered' => 'bg-success'
                    ];
                    $badgeClass = $badges[$progress->competency_status] ?? 'bg-secondary';
                    return '<span class="badge ' . $badgeClass . '">' . ucwords(str_replace('_', ' ', $progress->competency_status)) . '</span>';
                })
                ->addColumn('score_bar', function ($progress) {
                    $color = $progress->score >= 4 ? 'bg-success' : ($progress->score >= 3 ? 'bg-warning' : 'bg-danger');
                    $percent = ($progress->score / 5) * 100;
                    
                    return '
                        <div class="d-flex align-items-center">
                            <span class="fw-bold me-2">' . $progress->score . '/5</span>
                            <div class="progress flex-grow-1" style="height: 6px; width: 50px;">
                                <div class="progress-bar ' . $color . '" style="width: ' . $percent . '%"></div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('feedback', function ($progress) {
                    return $progress->feedback 
                        ? '<span class="text-truncate d-inline-block" style="max-width: 150px;" title="' . e($progress->feedback) . '">' . e($progress->feedback) . '</span>' 
                        : '<span class="text-muted">-</span>';
                })
                ->rawColumns(['competency_badge', 'score_bar', 'feedback'])
                ->make(true);
        }

        return view('admin.progress.index');
    }
}
