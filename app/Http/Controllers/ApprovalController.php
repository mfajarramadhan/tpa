<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

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

        // ambil payment registration
        $payment = Payment::where('student_id', $student->id)
            ->where('type', 'registration')
            ->first();

        // kalau belum upload bukti
        if (!$payment || !$payment->proof_file) {
            return back()->with('error', 'Bukti pembayaran belum diupload');
        }

        // SET PAYMENT JADI PAID
        $payment->update([
            'status' => 'paid'
        ]);

        // update siswa di table student
        $student->update([
            'status' => 'aktif',
            'classroom_id' => $request->classroom_id
        ]);

        // update user siswa juga di table user
        if ($student->user) {
            $student->user->update([
                'status' => 'aktif'
            ]);
        }

        return back()->with('success', 'Siswa berhasil di-approve & dimasukkan ke kelas');
    }

    public function rejectStudent(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string'
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'status' => 'ditolak',
            'reject_reason' => $request->reject_reason
        ]);

        if ($student->user) {
            $student->user->update([
                'status' => 'nonaktif'
            ]);
        }

        return back()->with('success', 'Siswa ditolak');
    }

    public function rejected()
    {
        $students = Student::where('status', 'ditolak')
            ->with('parent')
            ->latest()
            ->get();

        return view('students.rejected', compact('students'));
    }
}
