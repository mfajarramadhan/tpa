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

        // 🔹 ambil absensi sesi sekarang
        $attendance = Attendance::with('details')
            ->where([
                'classroom_id' => $classroomId,
                'date' => now()->toDateString(),
                'session' => $session
            ])
            ->first();

        // 🔹 ambil absensi pagi
        $attendancePagi = Attendance::with('details')
            ->where([
                'classroom_id' => $classroomId,
                'date' => now()->toDateString(),
                'session' => 'pagi'
            ])
            ->first();

        $details = [];
        $lockedStudents = [];

        // 🔥 PRIORITAS 1: SESSION SAAT INI
        if ($attendance) {
            foreach ($attendance->details as $d) {
                $details[$d->student_id] = $d;

                // 🔒 LOCK HANYA JIKA BUKAN ALPHA
                if ($d->status !== 'alpha') {
                    $lockedStudents[$d->student_id] = true;
                }
            }
        }

        // 🔥 PRIORITAS 2: FALLBACK PAGI (KHUSUS SORE)
        if ($session === 'sore' && $attendancePagi) {
            foreach ($attendancePagi->details as $d) {

                // kalau belum ada di sore → ambil dari pagi
                if (!isset($details[$d->student_id])) {
                    $details[$d->student_id] = $d;
                }

                // 🔒 LOCK HANYA JIKA BUKAN ALPHA
                if ($d->status !== 'alpha') {
                    $lockedStudents[$d->student_id] = true;
                }
            }
        }

        return view('attendances.create', compact(
            'classroom',
            'attendance',
            'session',
            'details',
            'lockedStudents'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'session' => 'required|in:pagi,sore',
            'date' => 'required|date',
            'students' => 'required|array'
        ]);

        // 🔹 HEADER ABSENSI
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

        // 🔹 AMBIL DATA PAGI (UNTUK LOCK)
        $attendancePagi = Attendance::with('details')
            ->where([
                'classroom_id' => $request->classroom_id,
                'date' => now()->toDateString(),
                'session' => 'pagi'
            ])
            ->first();

        $pagiDetails = [];

        if ($attendancePagi) {
            foreach ($attendancePagi->details as $d) {
                $pagiDetails[$d->student_id] = $d;
            }
        }

        // 🔹 AMBIL DATA SORE (UNTUK PROTEKSI BALIK KE PAGI)
        $attendanceSore = Attendance::with('details')
            ->where([
                'classroom_id' => $request->classroom_id,
                'date' => now()->toDateString(),
                'session' => 'sore'
            ])
            ->first();

        $soreDetails = [];

        if ($attendanceSore) {
            foreach ($attendanceSore->details as $d) {
                $soreDetails[$d->student_id] = $d;
            }
        }

        // 🔹 LOOP SEMUA SISWA (UPSERT, NO DELETE)
        foreach ($request->students as $studentId => $data) {

            // =========================================
            // 🌆 SESI SORE
            // =========================================
            if ($request->session === 'sore') {

                $pagi = $pagiDetails[$studentId] ?? null;

                if ($pagi && $pagi->status !== 'alpha') {
                    // 🔒 LOCK TOTAL (hadir, izin, sakit)
                    $status = $pagi->status;
                    $note = $pagi->note;

                } else {
                    // ✔ alpha / belum ada → boleh diubah
                    if (isset($data['hadir'])) {
                        $status = 'hadir';
                        $note = null;
                    } else {
                        $status = $data['status'] ?? 'alpha';
                        $note = $data['note'] ?? null;
                    }
                }

            // =========================================
            // 🌞 SESI PAGI
            // =========================================
            } else {

                $sore = $soreDetails[$studentId] ?? null;

                // 🔒 JANGAN OVERWRITE HASIL SORE
                if ($sore && $sore->status !== 'alpha') {
                    continue;
                }

                if (isset($data['hadir'])) {
                    $status = 'hadir';
                    $note = null;
                } else {
                    $status = $data['status'] ?? 'alpha';
                    $note = $data['note'] ?? null;
                }
            }

            // 🔥 UPSERT (AMAN)
            AttendanceDetail::updateOrCreate(
                [
                    'attendance_id' => $attendance->id,
                    'student_id' => $studentId
                ],
                [
                    'status' => $status,
                    'note' => $note
                ]
            );
        }

        return redirect()->route('attendances.create', [
            'classroom_id' => $request->classroom_id,
            'session' => $request->session
        ])->with('success', 'Absensi berhasil disimpan');
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
