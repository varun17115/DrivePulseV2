<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $payments = Payment::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.payments.index', compact('payments', 'student'));
    }

    public function print(Payment $payment)
    {
        $student = Auth::user()->student;
        if (!$student || $payment->student_id !== $student->id) abort(403);

        $pdf = Pdf::loadView('student.payments.receipt_pdf', compact('payment', 'student'));
        return $pdf->stream('Receipt_'.$payment->invoice_number.'.pdf');
    }
}
