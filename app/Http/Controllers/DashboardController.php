<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔷 SUPERADMIN
        if ($user->hasRole('superadmin')) {

            $totalStudents = Student::count();
            $totalTeachers = User::role('guru')->count();
            $pendingStudents = Student::where('status', 'nonaktif')->count();
            $pendingPayments = Payment::where('status', 'pending')->count();

            return view('dashboard.superadmin', compact(
                'totalStudents',
                'totalTeachers',
                'pendingStudents',
                'pendingPayments'
            ));
        }

        // 🔷 GURU
        if ($user->hasRole('guru')) {

            $today = now()->toDateString();

            $totalStudents = Student::where('status', 'aktif')->count();
            $todayAttendance = Attendance::where('date', $today)->count();
            $assignments = Assignment::where('created_by', $user->id)->count();

            return view('dashboard.guru', compact(
                'totalStudents',
                'todayAttendance',
                'assignments'
            ));
        }

        // Jika guru sudah tidak aktif
        if ($user->hasRole('guru') && $user->status == 'nonaktif') {
            return view('dashboard.nonaktif');
        }

        // 🔷 ORANG TUA
        if ($user->hasRole('orang_tua')) {

            $students = $user->students()->with('classroom')->get();

            $payments = Payment::whereHas('student', function ($q) use ($user) {
                $q->where('parent_id', $user->id);
            })->get();

            return view('dashboard.orangtua', compact('students', 'payments'));
        }

        // 🔷 SISWA
        if ($user->hasRole('siswa')) {

            $student = $user->student;

            $assignments = Assignment::where('classroom_id', $student->classroom_id)
                ->orderBy('deadline', 'asc')
                ->get();

            return view('dashboard.siswa', compact('assignments'));
        }

        abort(403);
    }
    
}
