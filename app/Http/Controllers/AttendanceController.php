<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index(Request $request)
    {
        $query = Attendance::with('student', 'classroom');

        if ($request->classroom_id) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }

        $attendances = $query->get();

        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classrooms = Classroom::all();

        $students = [];

        if ($request->classroom_id) {
            $students = Student::where('classroom_id', $request->classroom_id)
                ->where('status', 'aktif')
                ->get();
        }

        return view('attendances.create', compact('classrooms', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'date' => 'required|date',
            'students' => 'required|array'
        ]);

        foreach ($request->students as $studentId => $data) {

            // 🔥 Cegah double absensi
            $exists = Attendance::where('student_id', $studentId)
                ->where('date', $request->date)
                ->exists();

            if ($exists) {
                continue;
            }

            $status = isset($data['hadir']) ? 'hadir' : $data['status'];

            Attendance::create([
                'student_id' => $studentId,
                'classroom_id' => $request->classroom_id,
                'date' => $request->date,
                'status' => $status,
                'created_by' => Auth::user()->id
            ]);
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
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
}
