<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $evaluations = Progress::where('student_id', $student->id)
            ->with(['trainer.user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->get();

        $avgScore = $evaluations->avg('score') ?? 0;

        return view('student.progress.index', compact('evaluations', 'avgScore'));
    }
}
