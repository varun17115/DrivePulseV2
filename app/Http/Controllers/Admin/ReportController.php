<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Certificate;
use App\Models\MockTest;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\Vehicle;
use App\Models\VehicleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(6)->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 1. Monthly Revenue & Expenses
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyExpenses = VehicleService::whereBetween('service_date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(service_date, "%Y-%m") as month, SUM(cost) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 2. Booking Status Distribution
        $bookingStatus = Booking::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 3. Mock Test Pass vs Fail Rate
        $mockTestStats = MockTest::selectRaw('result, count(*) as count')
            ->groupBy('result')
            ->pluck('count', 'result')
            ->toArray();

        // 4. Student Enrollment Trend
        $studentEnrollments = Student::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Financial Summary
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalMaintenance = VehicleService::sum('cost');
        $netIncome = $totalRevenue - $totalMaintenance;

        return view('admin.reports.index', compact(
            'monthlyRevenue',
            'monthlyExpenses',
            'bookingStatus',
            'mockTestStats',
            'studentEnrollments',
            'totalRevenue',
            'totalMaintenance',
            'netIncome',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'students');
        $format = $request->input('format', 'excel'); // 'excel' (csv) or 'pdf'

        $report = $this->getReportData($type);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf_template', [
                'title' => $report['title'],
                'subtitle' => $report['subtitle'],
                'headers' => $report['headers'],
                'data' => $report['rows'],
            ])->setPaper('a4', $report['orientation'] ?? 'portrait');

            return $pdf->download(strtolower(str_replace(' ', '_', $report['title'])) . '_' . now()->format('Ymd_His') . '.pdf');
        }

        // CSV / Excel Export
        $fileName = strtolower(str_replace(' ', '_', $report['title'])) . '_' . now()->format('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($report) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $report['headers']);

            foreach ($report['rows'] as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    private function getReportData(string $type): array
    {
        switch ($type) {
            case 'trainers':
                $trainers = Trainer::with('user')->get();
                $rows = [];
                foreach ($trainers as $t) {
                    $rows[] = [
                        $t->id,
                        $t->user->name ?? 'N/A',
                        $t->user->email ?? 'N/A',
                        $t->user->phone ?? 'N/A',
                        $t->specialization ?? 'General',
                        $t->experience_years . ' Years',
                        '₹' . number_format($t->salary ?? 0, 2),
                        ucfirst($t->status),
                        $t->created_at ? $t->created_at->format('Y-m-d') : 'N/A'
                    ];
                }
                return [
                    'title' => 'Trainers Report',
                    'subtitle' => 'All Registered Driving Instructors',
                    'headers' => ['ID', 'Name', 'Email', 'Phone', 'Specialization', 'Experience', 'Salary', 'Status', 'Joined Date'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];

            case 'attendance':
                $attendance = Attendance::with(['booking.student.user', 'booking.trainer.user'])->latest()->get();
                $rows = [];
                foreach ($attendance as $a) {
                    $rows[] = [
                        $a->id,
                        $a->booking->booking_date ? Carbon::parse($a->booking->booking_date)->format('Y-m-d') : 'N/A',
                        $a->booking->student->user->name ?? 'N/A',
                        $a->booking->trainer->user->name ?? 'N/A',
                        ucfirst($a->status),
                        $a->check_in_time ? Carbon::parse($a->check_in_time)->format('H:i A') : 'N/A',
                        $a->trainer_remarks ?? '-'
                    ];
                }
                return [
                    'title' => 'Attendance Report',
                    'subtitle' => 'Student Lesson Attendance Log',
                    'headers' => ['ID', 'Date', 'Student', 'Trainer', 'Status', 'Check-In Time', 'Remarks'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];

            case 'payments':
                $payments = Payment::with('student.user')->latest()->get();
                $rows = [];
                foreach ($payments as $p) {
                    $rows[] = [
                        $p->invoice_number,
                        $p->student->user->name ?? 'N/A',
                        '₹' . number_format($p->amount, 2),
                        ucfirst($p->payment_method),
                        $p->transaction_id ?? '-',
                        ucfirst($p->status),
                        $p->paid_at ? Carbon::parse($p->paid_at)->format('Y-m-d H:i') : 'N/A'
                    ];
                }
                return [
                    'title' => 'Payments & Revenue Report',
                    'subtitle' => 'Fee Transactions & Receipts',
                    'headers' => ['Invoice #', 'Student', 'Amount', 'Method', 'Txn ID', 'Status', 'Payment Date'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];

            case 'mock_tests':
                $tests = MockTest::with('student.user')->latest()->get();
                $rows = [];
                foreach ($tests as $t) {
                    $rows[] = [
                        $t->test_code,
                        $t->student->user->name ?? 'N/A',
                        $t->score . ' / ' . $t->total_questions,
                        number_format($t->percentage, 1) . '%',
                        strtoupper($t->result),
                        gmdate('i\m s\s', $t->time_taken_seconds),
                        $t->created_at ? $t->created_at->format('Y-m-d H:i') : 'N/A'
                    ];
                }
                return [
                    'title' => 'Mock Tests Performance Report',
                    'subtitle' => 'Theory Test Evaluations',
                    'headers' => ['Test Code', 'Student', 'Score', 'Percentage', 'Result', 'Time Taken', 'Attempt Date'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];

            case 'vehicles':
                $vehicles = Vehicle::withSum('services', 'cost')->get();
                $rows = [];
                foreach ($vehicles as $v) {
                    $rows[] = [
                        $v->id,
                        $v->name . ' (' . $v->model . ')',
                        $v->registration_number,
                        ucfirst($v->type),
                        ucfirst($v->status),
                        $v->insurance_expiry ? Carbon::parse($v->insurance_expiry)->format('Y-m-d') : 'N/A',
                        '₹' . number_format($v->services_sum_cost ?? 0, 2)
                    ];
                }
                return [
                    'title' => 'Vehicles & Fleet Report',
                    'subtitle' => 'Fleet Status & Maintenance Expenses',
                    'headers' => ['ID', 'Vehicle', 'Reg Number', 'Type', 'Status', 'Insurance Expiry', 'Total Maintenance'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];

            case 'certificates':
                $certificates = Certificate::with('student.user')->latest()->get();
                $rows = [];
                foreach ($certificates as $c) {
                    $rows[] = [
                        $c->certificate_number,
                        $c->student->user->name ?? 'N/A',
                        $c->course_name,
                        $c->grade ?? 'Pass',
                        $c->issue_date ? Carbon::parse($c->issue_date)->format('Y-m-d') : 'N/A',
                        ucfirst($c->status),
                        route('verify-certificate', $c->qr_code_hash)
                    ];
                }
                return [
                    'title' => 'Certificates Issued Report',
                    'subtitle' => 'Official Course Completion Certificates',
                    'headers' => ['Cert #', 'Student', 'Course', 'Grade', 'Issue Date', 'Status', 'Verification URL'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];

            case 'students':
            default:
                $students = Student::with('user')->get();
                $rows = [];
                foreach ($students as $s) {
                    $rows[] = [
                        $s->id,
                        $s->user->name ?? 'N/A',
                        $s->user->email ?? 'N/A',
                        $s->user->phone ?? 'N/A',
                        $s->license_type ?? 'Standard',
                        '₹' . number_format($s->total_fee ?? 0, 2),
                        '₹' . number_format($s->paid_amount ?? 0, 2),
                        '₹' . number_format($s->due_amount ?? 0, 2),
                        ucfirst($s->status),
                        $s->admission_date ? Carbon::parse($s->admission_date)->format('Y-m-d') : ($s->created_at ? $s->created_at->format('Y-m-d') : 'N/A')
                    ];
                }
                return [
                    'title' => 'Students Report',
                    'subtitle' => 'All Enrolled Students Summary',
                    'headers' => ['ID', 'Name', 'Email', 'Phone', 'License Type', 'Total Fee', 'Paid', 'Due', 'Status', 'Admission Date'],
                    'rows' => $rows,
                    'orientation' => 'landscape'
                ];
        }
    }
}
