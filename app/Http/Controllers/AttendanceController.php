<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil semua kelas + jumlah siswa
        $classrooms = Classroom::withCount('students')->get();

        return view('attendances.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classroomId = $request->classroom_id;
        $session = $request->session ?? 'pagi';

        $classroom = Classroom::with('students')->findOrFail($classroomId);

        //  absensi + detailnya
        $attendance = Attendance::with('details')
            ->where([
                'classroom_id' => $classroomId,
                'date' => now()->toDateString(),
                'session' => $session
            ])
            ->first();

        $details = [];

        if ($attendance) {
            foreach ($attendance->details as $d) {
                $details[$d->student_id] = $d;
            }
        }

        return view('attendances.create', compact(
            'classroom',
            'attendance',
            'session',
            'details'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'session' => 'required|in:pagi,sore',
            'date' => 'required|date',
            'students' => 'required|array'
        ]);

        // 🔹 BUAT / AMBIL HEADER ABSENSI
        $attendance = Attendance::firstOrCreate(
            [
                'classroom_id' => $request->classroom_id,
                'date' => now()->toDateString(),
                'session' => $request->session,
            ],
            [
                'created_by' => auth()->id()
            ]
        );

        // HAPUS DETAIL LAMA (BIAR BISA EDIT ULANG)
        $attendance->details()->delete();

        // LOOP SEMUA SISWA
        foreach ($request->students as $studentId => $data) {
            // ✔ LOGIC UTAMA
            // jika checkbox hadir dicentang → hadir
            // jika tidak → ambil dari dropdown/alpha

                if (isset($data['hadir'])) {
                    $status = 'hadir';
                    $note = null;
                } else {
                    $status = $data['status'] ?? 'alpha';
                    $note = $data['note'] ?? null;
                }

                AttendanceDetail::create([
                    'attendance_id' => $attendance->id,
                    'student_id' => $studentId,
                    'status' => $status,
                    'note' => $note,
                ]);
            }

        return redirect()->route('attendances.create', ['classroom_id' => $request->classroom_id, 'session' => $request->session])->with('success', 'Absensi berhasil disimpan');    
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $students = $classroom->students;

        // cek apakah sudah ada absensi hari ini
        $attendance = Attendance::where('classroom_id', $classroom->id)
            ->whereDate('date', today())
            ->first();

        return view('attendances.show', compact('classroom', 'students', 'attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function studentRecap($id)
    {
        $student = Student::findOrFail($id);

        $attendances = Attendance::where('student_id', $id)->get();

        $hadir = $attendances->where('status', 'hadir')->count();
        $izin  = $attendances->where('status', 'izin')->count();
        $alpha = $attendances->where('status', 'alpha')->count();

        return view('attendances.recap_student', compact(
            'student',
            'hadir',
            'izin',
            'alpha',
            'attendances'
        ));
    }

    public function student($studentId)
    {
        $student = Student::with('classroom')->findOrFail($studentId);

        $attendances = Attendance::where('student_id', $studentId)
            ->latest()
            ->get();

        return view('attendances.student', compact('student', 'attendances'));
    }
}
