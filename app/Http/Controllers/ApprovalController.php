<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\Student;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ApprovalController extends Controller
{
    public function index()
    {
        $students = Student::where('status', 'nonaktif')
            ->with(['parent', 'user'])
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $classrooms = Classroom::withCount('students')->get();

        return view('approval.index', compact('students', 'classrooms'));
    }


    public function show(Student $student)
    {
        $classrooms = Classroom::withCount('students')->get();

        return view(
            'approval.show',
            compact(
                'student',
                'classrooms'
            )
        );
    }


    // Pendaftaran disetujui
    public function approveStudent(Request $request, Student $student)
    {
         $request->validate([
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

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

        // Kirim notifikasi ke orangtua siswa
        $parent = $student->parent;

        if ($parent) {
            $parent->notify(
                new PaymentApprovedNotification($payment)
            );
        }

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

        return redirect()->route('approval.students.index')->with('success', 'Pendaftaran berhasil disetujui! Siswa terdaftar kedalam kelas');
    }


    // Pendaftaran ditolak
    public function rejectStudent(Request $request, Student $student)
    {
        $request->validate([
            'reject_reason' => 'required|string'
        ]);

        $student->update([
            'status' => 'ditolak',
            'reject_reason' => $request->reject_reason
        ]);


        // Ambil pembayaran registrasi
        $payment = Payment::where('student_id', $student->id)
            ->where('type', 'registration')
            ->first();

        if ($payment) {

            $payment->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason
            ]);

            /// Kirim notifikasi ke orangtua
            $parent = $student->parent;

            if ($parent) {
                $parent->notify(
                    new PaymentRejectedNotification($payment)
                );
            }
        }

        if ($student->user) {
            $student->user->update([
                'status' => 'nonaktif'
            ]);
        }

        return redirect()->route('approval.students.index')->with('success', 'Pendaftaran ditolak! Notifikasi telah dikirim ke orang tua siswa.');
    }

    public function rejected()
    {
        $students = Student::where('status', 'ditolak')
            ->with(['parent', 'user'])
            ->latest()
            ->get();

        return view(
            'approval.rejected',
            compact('students')
        );
    }

    public function showRejected(Student $student)
    {
        $classrooms = Classroom::withCount('students')->get();

        return view(
            'approval.show-rejected',
            compact(
                'student',
                'classrooms'
            )
        );
    }
}
