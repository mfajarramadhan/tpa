<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classroom;

class ApprovalController extends Controller
{
    public function students()
    {
        $students = Student::where('status', 'nonaktif')
            ->with(['parent', 'user'])
            ->get();

        $classrooms = Classroom::all();

        return view('approval.students', compact('students', 'classrooms'));
    }

    public function approveStudent(Request $request, $id)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'status' => 'aktif',
            'classroom_id' => $request->classroom_id
        ]);

        return back()->with('success', 'Siswa berhasil di-approve & dimasukkan ke kelas');
    }

    public function rejectStudent($id)
    {
        $student = Student::findOrFail($id);

        $student->update([
            'status' => 'nonaktif'
        ]);

        return back()->with('success', 'Siswa ditolak');
    }

    public function __construct()
    {
        $this->middleware('role:superadmin');
    }
}
