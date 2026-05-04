<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
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
            $pendingPayments = Payment::where('status', 'pending')->where('type', 'monthly')->count();            
            $rejectedStudents = Student::where('status', 'ditolak')->count();

            return view('dashboard.superadmin', compact(
                'totalStudents',
                'totalTeachers',
                'pendingStudents',
                'pendingPayments',
                'rejectedStudents'
            ));
        }

        // 🔷 GURU
        if ($user->hasRole('guru')) {

            $today = now()->toDateString();

            $totalStudents = Student::where('status', 'aktif')->count();
            $todayAttendance = Attendance::where('date', $today)->count();
            $materials = Material::where('is_task', false)->count();
            $tasks = Material::where('is_task', true)->count();

            return view('dashboard.guru', compact(
                'totalStudents',
                'todayAttendance',
                'materials',
                'tasks'
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

        // ambil tugas berdasarkan classroom lewat subject
        $tasks = Material::whereHas('subject', function ($q) use ($student) {
            $q->where('classroom_id', $student->classroom_id);
        })
        ->where('is_task', 1)
        ->with('subject')
        ->latest()
        ->get();

        // ambil materi berdasarkan classroom lewat subject
        $materials = Material::whereHas('subject', function ($q) use ($student) {
            $q->where('classroom_id', $student->classroom_id);
        })
        ->where('is_task', 0)
        ->with('subject')
        ->latest()
        ->get();

        // total tugas
        $totalTasks = $tasks->count();
        $totalMaterials = $materials->count();

        return view('dashboard.siswa', compact('tasks', 'totalTasks', 'totalMaterials'));
    }

        abort(403);
    }
    
}
