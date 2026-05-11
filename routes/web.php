<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Profile
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


    // Route Mapel
    Route::get('/learning', [SubjectController::class, 'index'])->name('learning.index');
    Route::get('/learning/subject/{subject}', [SubjectController::class, 'show'])->name('learning.subject');
    Route::get('/learning/{classroom}', [SubjectController::class, 'classroom'])->name('learning.classroom');
    Route::get('/learning/{classroom}/create', [SubjectController::class, 'create'])->name('learning.subject.create');
    Route::post('/learning/{classroom}', [SubjectController::class, 'store'])->name('learning.subject.store');
    Route::get('/learning/subject/{subject}/edit', [SubjectController::class, 'edit'])->name('learning.subject.edit');
    Route::put('/learning/subject/{subject}', [SubjectController::class, 'update'])->name('learning.subject.update');
    Route::delete('/learning/subject/{subject}', [SubjectController::class, 'destroy'])->name('learning.subject.destroy');


    // Route Kelola Materi
    Route::get('/materials/create/{subject}', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    Route::get('/materials/{material}/submissions', [SubmissionController::class, 'index'])->name('materials.submissions');


    // Route Pengumpulan Tugas
    Route::get('/submissions/create/{material}', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions/{material}', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
    Route::post('/submissions/{submission}/complete', [SubmissionController::class, 'complete'])->name('submissions.complete');
    Route::post('/submissions/{submission}/revise', [SubmissionController::class, 'revise'])->name('submissions.revise');
    Route::post('/submissions/{id}/complete', [SubmissionController::class, 'complete'])->name('submissions.complete');
    Route::post('/submissions/{id}/revise', [SubmissionController::class, 'revise'])->name('submissions.revise');


    // Absensi
    Route::get('/attendance/student/{student}', [AttendanceController::class, 'student'])
        ->name('attendance.student');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::get('/attendances/{classroom}', [AttendanceController::class, 'show'])->name('attendances.show');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    // Rekap Absensi
    Route::get('/attendance-recaps', [AttendanceController::class, 'recap'])->name('attendance.recap');
    Route::post('/attendance-recaps/update/{detail}', [AttendanceController::class, 'updateRecap'])->name('attendance.recap.update');


    // Detail Pembayaran
    Route::resource('payments', PaymentController::class);
    // Detail pembayaran per siswa
    Route::get('/students/{student}/payments', [PaymentController::class, 'show'])->name('payments.student.show');
    Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/{payment}/unapprove', [PaymentController::class, 'unapprove'])->name('payments.unapprove');


    // Khusus Superadmin
    Route::middleware('role:superadmin')->group(function () {
        // Approval Siswa Baru
        Route::get('/approval/students', [ApprovalController::class, 'students'])->name('approval.students');
        Route::post('/approval/students/{id}/approve', [ApprovalController::class, 'approveStudent'])->name('approval.students.approve');
        Route::post('/approval/students/{id}/reject', [ApprovalController::class, 'rejectStudent'])->name('approval.students.reject');

        // Kelola User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');


        // Pengaturan Biaya
        Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
        // Fees = Kenaikan biaya
        Route::post('/fees/update', [FeeController::class, 'update'])->name('fees.update');
        // Adjustment = Penurunan biaya
        Route::post('/fees/adjustment', [AdjustmentController::class, 'apply'])->name('fees.adjustment.apply');
    });
});

require __DIR__.'/auth.php';
