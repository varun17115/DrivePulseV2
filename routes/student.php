<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\MockTestController;
use App\Http\Controllers\Student\BookingController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\PaymentController;
use App\Http\Controllers\Student\CertificateController;

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lesson Bookings (Self-service)
    Route::resource('bookings', BookingController::class)->except(['edit', 'update']);
    Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Attendance & Progress Details
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

    // Payments & Invoices
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');

    // Certificates
    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate.index');
    Route::get('/certificate/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');

    // Student Mock Tests
    Route::get('/mock-tests', [MockTestController::class, 'index'])->name('mock-tests.index');
    Route::get('/mock-tests/start', [MockTestController::class, 'start'])->name('mock-tests.start');
    Route::post('/mock-tests/submit', [MockTestController::class, 'submit'])->name('mock-tests.submit');
    Route::get('/mock-tests/{mockTest}', [MockTestController::class, 'show'])->name('mock-tests.show');
});
