<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
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


    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    // Route daftar ulang
    Route::get('/students/rejected', [ApprovalController::class, 'rejected'])->name('students.rejected');
    // Route Siswa
    Route::resource('students', StudentController::class);
    // Route Approval
    Route::get('/students/{student}/reapply', [StudentController::class, 'reapply'])->name('students.reapply');
    Route::post('/students/{student}/reapply', [StudentController::class, 'submitReapply'])->name('students.reapply.submit');


    // Route Kelas 
    Route::get('/learning', [ClassroomController::class, 'index'])->name('learning.index');
    Route::get('/learning/{classroom}', [ClassroomController::class, 'show'])->name('learning.classroom');
    Route::get('/learning/classroom/create', [ClassroomController::class, 'create'])->name('learning.classroom.create');
    Route::post('/learning/classroom', [ClassroomController::class, 'store'])->name('learning.classroom.store');
    Route::get('/learning/classroom/{classroom}/edit', [ClassroomController::class, 'edit'])->name('learning.classroom.edit');
    Route::put('/learning/classroom/{classroom}', [ClassroomController::class, 'update'])->name('learning.classroom.update');
    Route::delete('/learning/classroom/{classroom}', [ClassroomController::class, 'destroy'])->name('learning.classroom.destroy');
    
    
    // Route Mapel
    Route::get('/learning/subject/{subject}', [SubjectController::class, 'show'])->name('learning.subject');
    Route::get('/learning/{classroom}/create', [SubjectController::class, 'create'])->name('learning.subject.create');
    Route::post('/learning/{classroom}', [SubjectController::class, 'store'])->name('learning.subject.store');
    Route::get('/learning/subject/{subject}/edit', [SubjectController::class, 'edit'])->name('learning.subject.edit');
    Route::put('/learning/subject/{subject}', [SubjectController::class, 'update'])->name('learning.subject.update');
    Route::delete('/learning/subject/{subject}', [SubjectController::class, 'destroy'])->name('learning.subject.destroy');


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


    // Khusus Superadmin
    Route::middleware('role:superadmin')->group(function () {
        // Approval Siswa Baru
        Route::get('/approval/students', [ApprovalController::class, 'show'])->name('approval.show');
        Route::post('/approval/students/{student}/approve', [ApprovalController::class, 'approveStudent'])->name('approval.students.approve');
        
        Route::post('/approval/students/{student}/reject', [ApprovalController::class, 'rejectStudent'])->name('approval.students.reject');


        // Detail pembayaran per siswa
        Route::get('/students/{student}/payments', [PaymentController::class, 'show'])->name('payments.student.show');
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        // batalkan approve iuran bulanan (unapprove)
        Route::post('/payments/{payment}/unapprove', [PaymentController::class, 'unapprove'])->name('payments.unapprove');


        // Kelola User
        Route::post('/users/{id}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');


        // Kenaikan Kelas 
        Route::get('/promotions',[PromotionController::class,'index'])->name('promotions.index');
        Route::get('/promotions/{classroom}',[PromotionController::class,'show'])->name('promotions.show');
        Route::post('/promotions/{classroom}',[PromotionController::class,'process'])->name('promotions.process');

        
        // Pengaturan Biaya
        Route::get('/fees', [FeeController::class, 'index'])->name('fees.index');
        // Fees = Kenaikan biaya
        Route::post('/fees/update', [FeeController::class, 'update'])->name('fees.update');
        // Adjustment = Penurunan biaya
        Route::post('/fees/adjustment', [AdjustmentController::class, 'apply'])->name('fees.adjustment.apply');


        // Route Tahun Akademik
        Route::resource('academic-years', AcademicYearController::class)->except('destroy');
        Route::post('/academic-years/{academicYear}/set-active', [AcademicYearController::class, 'setActive'])->name('academic-years.setActive');
    });

    // Khusus Guru
    Route::middleware('role:guru')->group(function () {
        // Route Kelola Materi
        Route::get('/materials/create/{subject}', [MaterialController::class, 'create'])->name('materials.create');
        Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
        Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
        Route::get('/materials/{material}/submissions', [SubmissionController::class, 'index'])->name('materials.submissions');
    });
});

require __DIR__.'/auth.php';
