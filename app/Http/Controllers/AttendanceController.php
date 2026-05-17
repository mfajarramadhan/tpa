<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
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
                'academic_year_id' => activeAcademicYear()->id,
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
                'academic_year_id' => activeAcademicYear()->id,
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
                'academic_year_id' => activeAcademicYear()->id,
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
        ])->with('success', 'Absensi berhasil disimpan!');
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

    // public function studentRecap($id)
    // {
    //     $student = Student::findOrFail($id);

    //     $attendances = Attendance::where('student_id', $id)->get();

    //     $hadir = $attendances->where('status', 'hadir')->count();
    //     $izin  = $attendances->where('status', 'izin')->count();
    //     $alpha = $attendances->where('status', 'alpha')->count();

    //     return view('attendances.recap_student', compact(
    //         'student',
    //         'hadir',
    //         'izin',
    //         'alpha',
    //         'attendances'
    //     ));
    // }

    // public function student($studentId)
    // {
    //     $student = Student::with('classroom')->findOrFail($studentId);

    //     $attendances = Attendance::where('student_id', $studentId)
    //         ->latest()
    //         ->get();

    //     return view('attendances.student', compact('student', 'attendances'));
    // }

    public function recap(Request $request)
    {
        // default tab daily filter
        $tab = $request->tab ?? 'daily';
        // weekly filter
        $month = $request->month ?? now()->month;
        $week = $request->week ?? 1;

        $user = auth()->user();

        // filter
        $date = $request->date ?? now()->toDateString();
        $classroomId = $request->classroom_id;

        // default
        $details = collect();

        /*
        =====================================================
        SUPERADMIN & GURU
        =====================================================
        */
        if ($user->hasAnyRole(['superadmin', 'guru'])) {

            // dropdown semua kelas
            $classrooms = Classroom::orderBy('name')->get();

        /*
        =====================================================
        ORANG TUA
        =====================================================
        */
        } elseif ($user->hasRole('orang_tua')) {

            // hanya kelas anaknya
            $classrooms = Classroom::whereIn(
                'id',
                $user->students->pluck('classroom_id')
            )->get();

            // auto pilih kelas pertama jika belum dipilih
            if (!$classroomId && $classrooms->count()) {
                $classroomId = $classrooms->first()->id;
            }

        /*
        =====================================================
        SISWA
        =====================================================
        */
        } elseif ($user->hasRole('siswa')) {

            // hanya kelas sendiri
            $classrooms = Classroom::where(
                'id',
                $user->student->classroom_id
            )->get();

            // auto pilih kelas sendiri
            $classroomId = $user->student->classroom_id;
        }

        /*
        =====================================================
        QUERY REKAP
        =====================================================
        */
        if ($classroomId) {

            // absensi sore
            $attendanceSore = Attendance::with('details.student')
                ->where('classroom_id', $classroomId)
                ->whereDate('date', $date)
                ->where('session', 'sore')
                ->first();

            // absensi pagi
            $attendancePagi = Attendance::with('details.student')
                ->where('classroom_id', $classroomId)
                ->whereDate('date', $date)
                ->where('session', 'pagi')
                ->first();

            $finalDetails = [];

            /*
            =====================================================
            PRIORITAS SORE
            =====================================================
            */
            if ($attendanceSore) {

                foreach ($attendanceSore->details as $detail) {

                    $detail->session = 'sore';

                    $finalDetails[$detail->student_id] = $detail;
                }
            }

            /*
            =====================================================
            FALLBACK PAGI
            =====================================================
            */
            if ($attendancePagi) {

                foreach ($attendancePagi->details as $detail) {

                    if (!isset($finalDetails[$detail->student_id])) {

                        $detail->session = 'pagi';

                        $finalDetails[$detail->student_id] = $detail;
                    }
                }
            }

            $details = collect($finalDetails);

            /*
            =====================================================
            FILTER ORANG TUA
            =====================================================
            */
            if ($user->hasRole('orang_tua')) {

                $studentIds = $user->students->pluck('id');

                $details = $details->whereIn('student_id', $studentIds);
            }

            /*
            =====================================================
            FILTER SISWA
            =====================================================
            */
            if ($user->hasRole('siswa')) {

                $details = $details->where(
                    'student_id',
                    $user->student->id
                );
            }

            // urut nama
            $details = $details->sortBy('student.name');
        }

        /*
        =====================================================
        REKAP BULANAN
        =====================================================
        */
        $monthlyData = collect();

        if ($tab == 'monthly' && $classroomId) {

            // filter
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;

            // Ambil siswa aktif di kelas saat ini
            // Alumni / siswa naik kelas history rekap absensi tidak ditampilkan (tersimpan di DB)
            $students = Student::where('classroom_id', $classroomId)
                ->orderBy('name')
                ->get();

            foreach ($students as $student) {

                /*
                =================================================
                AMBIL FINAL ATTENDANCE
                PRIORITAS SORE > PAGI
                =================================================
                */
                $attendanceDetails = AttendanceDetail::where('student_id', $student->id)

                    ->whereHas('attendance', function ($q) use ($classroomId, $month, $year) {

                        $q->where('classroom_id', $classroomId)
                            ->whereMonth('date', $month)
                            ->whereYear('date', $year);
                    })

                    ->with('attendance')
                    ->get()

                    // unique per tanggal
                    ->groupBy(fn($item) => $item->attendance->date)

                    // prioritaskan sore
                    ->map(function ($items) {

                        return $items
                            ->sortByDesc(fn($item) =>
                                $item->attendance->session == 'sore'
                            )
                            ->first();
                    });

                /*
                =================================================
                COUNT STATUS
                =================================================
                */
                $hadir = $attendanceDetails->where('status', 'hadir')->count();

                $izin = $attendanceDetails->where('status', 'izin')->count();

                $sakit = $attendanceDetails->where('status', 'sakit')->count();

                $alpha = $attendanceDetails->where('status', 'alpha')->count();

                $total = $hadir + $izin + $sakit + $alpha;

                $persentase = $total
                    ? round(($hadir / $total) * 100)
                    : 0;

                /*
                =================================================
                PUSH
                =================================================
                */
                $monthlyData->push([

                    'student' => $student,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'persentase' => $persentase

                ]);
            }
        }

        /*
        =====================================================
        REKAP TAHUNAN
        =====================================================
        */
        $yearlyData = collect();

        if ($tab == 'yearly' && $classroomId) {

            // filter
            $year = $request->year ?? now()->year;

            // Ambil siswa aktif di kelas saat ini
            // Alumni / siswa naik kelas history rekap absensi tidak ditampilkan (tersimpan di DB)
            $students = Student::where('classroom_id', $classroomId)
                ->orderBy('name')
                ->get();

            foreach ($students as $student) {

                /*
                =================================================
                FINAL ATTENDANCE
                PRIORITAS SORE > PAGI
                =================================================
                */
                $attendanceDetails = AttendanceDetail::where('student_id', $student->id)

                    ->whereHas('attendance', function ($q) use ($classroomId, $year) {

                        $q->where('classroom_id', $classroomId)
                            ->whereYear('date', $year);
                    })

                    ->with('attendance')
                    ->get()

                    // unique per tanggal
                    ->groupBy(fn($item) => $item->attendance->date)

                    // prioritaskan sore
                    ->map(function ($items) {

                        return $items
                            ->sortByDesc(fn($item) =>
                                $item->attendance->session == 'sore'
                            )
                            ->first();
                    });

                /*
                =================================================
                COUNT STATUS
                =================================================
                */
                $hadir = $attendanceDetails->where('status', 'hadir')->count();

                $izin = $attendanceDetails->where('status', 'izin')->count();

                $sakit = $attendanceDetails->where('status', 'sakit')->count();

                $alpha = $attendanceDetails->where('status', 'alpha')->count();

                $total = $hadir + $izin + $sakit + $alpha;

                $persentase = $total
                    ? round(($hadir / $total) * 100)
                    : 0;

                /*
                =================================================
                PUSH
                =================================================
                */
                $yearlyData->push([

                    'student' => $student,

                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,

                    'persentase' => $persentase

                ]);
            }
        }

        return view('attendance-recaps.index', compact(
            'classrooms',
            'details',
            'date',
            'classroomId',
            'tab',
            'monthlyData',
            'yearlyData'
        ));
    }

    public function updateRecap(Request $request, AttendanceDetail $detail)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'note' => 'nullable|string|max:255'
        ]);

        $detail->update([
            'status' => $request->status,
            'note' => $request->note
        ]);

        return back()->with('success', 'Absensi berhasil diperbarui');
    }
}
