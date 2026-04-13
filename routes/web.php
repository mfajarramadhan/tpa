<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kelola User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Route Kelas
    Route::resource('students', StudentController::class);

    // Approval Siswa Baru
    Route::get('/approval/students', [ApprovalController::class, 'students'])->name('approval.students');
    Route::post('/approval/students/{id}/approve', [ApprovalController::class, 'approveStudent'])->name('approval.students.approve');
    Route::post('/approval/students/{id}/reject', [ApprovalController::class, 'rejectStudent'])->name('approval.students.reject');

    // Kehadiran
    Route::resource('attendances', AttendanceController::class);
    Route::get('/attendance/student/{id}', [AttendanceController::class, 'studentRecap'])
    ->name('attendance.student');

    // Pembayaran
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/student/{id}', [PaymentController::class, 'show'])
    ->name('payments.show');
    Route::post('/payments/{id}/approve', [PaymentController::class, 'approve'])
    ->name('payments.approve');
    Route::post('/payments/{id}/unapprove', [PaymentController::class, 'unapprove'])
    ->name('payments.unapprove');

    // Tugas dan Submission
    Route::resource('assignments', AssignmentController::class);

    Route::post('/assignments/{id}/submit', [AssignmentSubmissionController::class, 'store'])
        ->name('assignments.submit');
    Route::post('/assignments/{id}/submit', [AssignmentController::class, 'submit'])
        ->name('assignments.submit');
    Route::delete('/submissions/{id}', [AssignmentSubmissionController::class, 'destroy'])
        ->name('submissions.destroy');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
