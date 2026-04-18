<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeController;
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
    
    // Route Kelas khusus daftar ulang
    Route::get('/students/rejected', [ApprovalController::class, 'rejected'])->name('students.rejected');
    // Route Kelas
    Route::resource('students', StudentController::class);
    // Approval
    Route::get('/students/{id}/reapply', [StudentController::class, 'reapply'])->name('students.reapply');
    Route::post('/students/{id}/reapply', [StudentController::class, 'submitReapply'])->name('students.reapply.submit');
    
    // Khusus Superadmin
    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        // Approval Siswa Baru
        Route::get('/approval/students', [ApprovalController::class, 'students'])->name('approval.students');
        Route::post('/approval/students/{id}/approve', [ApprovalController::class, 'approveStudent'])->name('approval.students.approve');
        Route::post('/approval/students/{id}/reject', [ApprovalController::class, 'rejectStudent'])->name('approval.students.reject');

        // Kelola User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Pengaturan Biaya
        Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
        Route::post('/fees', [FeeController::class, 'update'])->name('fees.update');
    });

    // Kehadiran
    Route::resource('attendances', AttendanceController::class);
    Route::get('/attendance/student/{id}', [AttendanceController::class, 'studentRecap'])
    ->name('attendance.student');

    // Detail Pembayaran
    Route::resource('payments', PaymentController::class);

    // Detail per siswa
    Route::get('/students/{student}/payments', [PaymentController::class, 'show'])->name('payments.student.show');
    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/{payment}/unapprove', [PaymentController::class, 'unapprove'])->name('payments.unapprove');


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
