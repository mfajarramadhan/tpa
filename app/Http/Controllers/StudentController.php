<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Auth::user()->students()->with('classroom')->get();

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fee = Fee::first();
        return view('students.create', compact('fee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string',
            'nisn' => 'required|digits:10|unique:students,nisn',
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->age < 4) {
                        $fail('Usia anak minimal 4 tahun');
                    }
                }
            ],
            'gender' => 'required|in:L,P',
            'school_origin' => 'required|string|max:255',    
            'kk_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'birth_certificate_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'proof_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Upload file
        $kkPath = $request->file('kk_file')->store('kk', 'public');
        $aktaPath = $request->file('birth_certificate_file')->store('akta', 'public');
        $proofPath = $request->file('proof_file')->store('payments', 'public');

        // format tanggal lahir (ddmmyyyy)
        $birthDate = Carbon::parse($request->birth_date)->format('dmY');

        // Buat akun siswa
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(str_replace(' ', '', $request->name)) . $birthDate . '@mail.com', // gabungkan nama + tanggal lahir
            'password' => Hash::make($birthDate), // Generate password dari tanggal lahir
            'status' => 'nonaktif',
            'approval_status' => 'approved',
        ]);

        $user->assignRole('siswa');

        // Simpan data siswa
        $student = Student::create([
            'parent_id' => Auth::user()->id,
            'user_id' => $user->id,
            'classroom_id' => null,
            'nisn' => $request->nisn,
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'school_origin' => $request->school_origin,
            'kk_file' => $kkPath,
            'birth_certificate_file' => $aktaPath,
            'status' => 'nonaktif'
        ]);

        // Buat pembayaran registrasi
        Payment::create([
            'student_id' => $student->id,
            'type' => 'registration',
            'month' => null,
            'original_amount' => 100000,
            'amount' => 100000,
            'proof_file' => $proofPath,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Anak berhasil didaftarkan! Menunggu persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Student::with(['classroom', 'user'])
            ->findOrFail($id);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $this->authorizeStudent($student);

        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $student = Student::with('user')->findOrFail($id);

        $isAdmin = auth()->user()->hasRole('superadmin');

        // VALIDASI
        $rules = [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'school_origin' => 'nullable|string|max:255',
            'kk_file' => 'nullable|image|max:2048',
            'birth_certificate_file' => 'nullable|image|max:2048',
        ];

        // hanya admin wajib isi
        if ($isAdmin) {
            $rules['gender'] = 'required|in:L,P';
            $rules['email'] = 'required|email|unique:users,email,' . $student->user_id;
            $rules['classroom_id'] = 'required|exists:classrooms,id';
            $rules['nisn'] = 'required|string|max:10';
        }

        $request->validate($rules);

        // FORMAT PASSWORD (dmY)
        $birthDateFormatted = Carbon::parse($request->birth_date)->format('dmY');

        // UPDATE USER (LOGIN SISWA)
        $userData = [
            'name' => $request->name,
        ];

        if ($isAdmin) {
            $userData['email'] = $request->email;
        } else {
            // AUTO EMAIL SISWA (dmY)
            $userData['email'] = strtolower(str_replace(' ', '', $request->name)) . $birthDateFormatted . '@mail.com';
        }

        // OPTIONAL: kalau mau reset password otomatis saat edit
        $userData['password'] = Hash::make($birthDateFormatted);

        $student->user->update($userData);

        // HANDLE FILE UPLOAD
        if ($request->hasFile('kk_file')) {
            $student->kk_file = $request->file('kk_file')->store('kk', 'public');
        }

        if ($request->hasFile('birth_certificate_file')) {
            $student->birth_certificate_file = $request->file('birth_certificate_file')->store('akta', 'public');
        }

        // UPDATE DATA SISWA
        $studentData = [
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'school_origin' => $request->school_origin,
        ];

        if ($isAdmin) {
            $studentData['classroom_id'] = $request->classroom_id;
            $studentData['nisn'] = $request->nisn;
            $studentData['gender'] = $request->gender;
        }

        $student->update($studentData);

        return redirect()->route('students.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->authorizeStudent($student);

        $student->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
    
    private function authorizeStudent($student)
    {
        if ($student->parent_id !== Auth::id()) {
            abort(403);
        }
    }

    public function reapply($id)
    {
        $student = Student::findOrFail($id);

        // 🔒 hanya yang ditolak
        if ($student->status !== 'ditolak') {
            abort(403);
        }

        return view('students.reapply', compact('student'));
    }

    public function submitReapply(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        if ($student->status !== 'ditolak') {
            abort(403);
        }

        // VALIDASI (TAMBAH INI)
        $request->validate([
            'name' => 'required|string',
            'nisn' => [
                'required',
                'digits:10',
                Rule::unique('students', 'nisn')->ignore($student->id)
            ],
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'school_origin' => 'required'
        ]);

        // HANDLE FILE KK
        if ($request->hasFile('kk_file')) {

            if ($student->kk_file) {
                Storage::disk('public')->delete($student->kk_file);
            }

            $student->kk_file = $request->file('kk_file')->store('kk', 'public');
        }

        // HANDLE FILE AKTA
        if ($request->hasFile('birth_certificate_file')) {

            if ($student->birth_certificate_file) {
                Storage::disk('public')->delete($student->birth_certificate_file);
            }

            $student->birth_certificate_file = $request->file('birth_certificate_file')->store('akta', 'public');
        }

        // UPDATE DATA SISWA
        $student->update([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'school_origin' => $request->school_origin,
            'status' => 'nonaktif', // balik ke pending
            'reject_reason' => null
        ]);

        // HANDLE PAYMENT REGISTRATION
        $payment = Payment::where('student_id', $student->id)
            ->where('type', 'registration')
            ->first();

        if ($payment) {

            // jika upload bukti baru
            if ($request->hasFile('payment_proof')) {

                // hapus file lama
                if ($payment->proof_file) {
                    Storage::disk('public')->delete($payment->proof_file);
                }

                $newProof = $request->file('payment_proof')->store('payments', 'public');

                $payment->update([
                    'proof_file' => $newProof,
                    'status' => 'pending',
                    'paid_at' => now()
                ]);

            } else {

                // TIDAK upload baru → tetap pakai lama
                $payment->update([
                    'status' => 'pending'
                    // proof_file tetap
                    // paid_at tetap
                ]);
            }

        } else {

            // kalau belum ada (edge case)
            if ($request->hasFile('payment_proof')) {

                $newProof = $request->file('payment_proof')->store('payments', 'public');

                Payment::create([
                    'student_id' => $student->id,
                    'type' => 'registration',
                    'original_amount' => 100000,
                    'amount' => 100000,
                    'proof_file' => $newProof,
                    'status' => 'pending',
                    'paid_at' => now()
                ]);
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran ulang berhasil, menunggu persetujuan');
    }
}
