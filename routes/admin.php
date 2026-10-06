<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleServiceController;
use App\Http\Controllers\Admin\VehicleTimetableController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DuePaymentController;
use App\Http\Controllers\Admin\MailBroadcastController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\MockTestController;
use App\Http\Controllers\Admin\MockTestQuestionController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ProgressController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students & Admission Form
    Route::get('students/export', [StudentController::class, 'exportCsv'])->name('students.export');
    Route::get('students/{student}/admission-form', [StudentController::class, 'admissionForm'])->name('students.admission-form');
    Route::post('students/{student}/email-admission', [StudentController::class, 'emailAdmissionForm'])->name('students.email-admission');
    Route::resource('students', StudentController::class);

    // Trainers
    Route::get('trainers/export', [TrainerController::class, 'exportCsv'])->name('trainers.export');
    Route::resource('trainers', TrainerController::class);

    // Vehicles & Timetable
    Route::get('vehicles/timetable', [VehicleTimetableController::class, 'index'])->name('vehicles.timetable');
    Route::get('vehicles/export', [VehicleController::class, 'exportCsv'])->name('vehicles.export');
    Route::resource('vehicles', VehicleController::class);
    Route::get('vehicle-services', [VehicleServiceController::class, 'index'])->name('vehicle-services.index');
    Route::post('vehicles/{vehicle}/services', [VehicleController::class, 'addService'])->name('vehicles.services.store');
    Route::put('vehicles/services/{service}', [VehicleServiceController::class, 'update'])->name('vehicles.services.update');
    Route::delete('vehicles/services/{service}', [VehicleServiceController::class, 'destroy'])->name('vehicles.services.destroy');

    // Bookings & Scheduling
    Route::get('bookings/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/{booking}/attendance', [BookingController::class, 'recordAttendance'])->name('bookings.attendance.store');
    Route::post('bookings/{booking}/progress', [BookingController::class, 'recordProgress'])->name('bookings.progress.store');
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status.update');

    // Attendance & Progress Logs
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('progress', [ProgressController::class, 'index'])->name('progress.index');

    // Payments & Due Customers Manager
    Route::get('payments/export', [PaymentController::class, 'exportCsv'])->name('payments.export');
    Route::resource('payments', PaymentController::class);
    Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');

    Route::get('due-payments', [DuePaymentController::class, 'index'])->name('due-payments.index');
    Route::post('due-payments/record-installment', [DuePaymentController::class, 'recordInstallment'])->name('due-payments.record-installment');
    Route::post('due-payments/{student}/remind', [DuePaymentController::class, 'sendReminder'])->name('due-payments.remind');
    Route::post('due-payments/bulk-remind', [DuePaymentController::class, 'bulkRemind'])->name('due-payments.bulk-remind');
    Route::get('due-payments/export', [DuePaymentController::class, 'exportCsv'])->name('due-payments.export');

    // Email Broadcast & Offers (V1 offerMail)
    Route::get('broadcast', [MailBroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('broadcast/send', [MailBroadcastController::class, 'send'])->name('broadcast.send');
    Route::get('broadcast/{emailLog}', [MailBroadcastController::class, 'show'])->name('broadcast.show');

    // Activity Audit Logs (V1 viewLogs)
    Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::delete('logs/clear', [ActivityLogController::class, 'clear'])->name('logs.clear');
    Route::get('logs/export', [ActivityLogController::class, 'exportCsv'])->name('logs.export');

    // Mock Tests
    Route::resource('mock-questions', MockTestQuestionController::class)->except(['show']);
    Route::get('mock-tests', [MockTestController::class, 'index'])->name('mock-tests.index');
    Route::get('mock-tests/{mockTest}', [MockTestController::class, 'show'])->name('mock-tests.show');

    // Certificates
    Route::resource('certificates', CertificateController::class)->except(['edit', 'update', 'show']);
    Route::get('certificates/{certificate}/print', [CertificateController::class, 'print'])->name('certificates.print');
    Route::post('certificates/{certificate}/revoke', [CertificateController::class, 'revoke'])->name('certificates.revoke');

    // Reports & Settings
    Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

