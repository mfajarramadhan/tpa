<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
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
use App\Notifications\PaymentUploadedNotification;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Auth::user()->students()->with('classroom')->latest()->paginate(10)->withQueryString();

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
                    if (Carbon::parse($value)->age < 8) {
                        $fail('Usia anak minimal 8 tahun!');
                    }
                }
            ],
            'gender' => 'required|in:L,P',
            'school_origin' => 'required|string|max:255',    
            'school_grade' => 'required|string|max:20',
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

        // Tahun akademik
        $academicYear = AcademicYear::where('is_active', true)->first();
        if (!$academicYear) {
            return back()->with('error', 'Tahun ajaran aktif belum tersedia');
        }

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
            'academic_year_id' => $academicYear->id,
            'nisn' => $request->nisn,
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'school_origin' => $request->school_origin,
            'school_grade' => $request->school_grade,
            'kk_file' => $kkPath,
            'birth_certificate_file' => $aktaPath,
            'status' => 'nonaktif'
        ]);

        // Buat pembayaran registrasi
        $payment = Payment::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYear->id,
            'type' => 'registration',
            'month' => null,
            'original_amount' => 100000,
            'amount' => 100000,
            'proof_file' => $proofPath,
            'status' => 'pending'
        ]);

        // Kirim notifikasi ke superadmin
        $superadmins = User::role('superadmin')->get();
        
        foreach ($superadmins as $admin) {
            $admin->notify(
                new PaymentUploadedNotification($payment)
            );
        }

        return redirect()->route('dashboard')
            ->with('success', 'Anak berhasil didaftarkan! Menunggu persetujuan admin.');
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
    public function update(Request $request, Student $student)
    {
        $student->load('user');

        $isAdmin = auth()->user()->hasRole('superadmin');

        // Validasi
        $rules = [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'school_origin' => 'nullable|string|max:255',
            'school_grade' => 'required|string|max:20',

            'kk_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'birth_certificate_file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        // Khusus admin
        if ($isAdmin) {

            $rules['gender'] = 'required|in:L,P';

            $rules['email'] = [
                'required',
                'email',
                'unique:users,email,' . $student->user_id
            ];

            $rules['classroom_id'] = 'required|exists:classrooms,id';

            $rules['nisn'] = [
                'required',
                'digits:10',
                Rule::unique('students', 'nisn')->ignore($student->id)
            ];
        }

        $request->validate($rules);

        // Format password (ddmmyyyy)
        $birthDateFormatted = Carbon::parse($request->birth_date)
            ->format('dmY');

        // Handle file KK
        $kkPath = $student->kk_file;

        if ($request->hasFile('kk_file')) {

            // Hapus file lama
            if ($student->kk_file) {
                Storage::disk('public')->delete($student->kk_file);
            }

            $kkPath = $request->file('kk_file')
                ->store('kk', 'public');
        }

        // Handle file akta
        $aktaPath = $student->birth_certificate_file;

        if ($request->hasFile('birth_certificate_file')) {

            // Hapus file lama
            if ($student->birth_certificate_file) {

                Storage::disk('public')
                    ->delete($student->birth_certificate_file);
            }

            $aktaPath = $request->file('birth_certificate_file')
                ->store('akta', 'public');
        }

        // Update user login siswa
        $userData = [
            'name' => $request->name,

            // Reset password otomatis
            'password' => Hash::make($birthDateFormatted),
        ];

        // Admin bisa edit email
        if ($isAdmin) {

            $userData['email'] = $request->email;

        } else {

            // Auto generate email siswa
            $userData['email'] =
                strtolower(str_replace(' ', '', $request->name))
                . $birthDateFormatted
                . '@mail.com';
        }

        $student->user->update($userData);

        // Update data siswa
        $studentData = [
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'school_origin' => $request->school_origin,
            'school_grade' => $request->school_grade,

            'kk_file' => $kkPath,
            'birth_certificate_file' => $aktaPath,
        ];

        // Khusus admin
        if ($isAdmin) {

            $studentData['classroom_id'] = $request->classroom_id;
            $studentData['nisn'] = $request->nisn;
            $studentData['gender'] = $request->gender;
        }

        $student->update($studentData);

        return redirect()
            ->route('students.index')
            ->with('success', 'Data berhasil diperbarui');
    }


    // Hak akses
    private function authorizeStudent(Student $student)
    {
        // Superadmin boleh akses semua siswa
        if (Auth::user()->hasRole('superadmin')) {
            return;
        }

        // Orang tua hanya bisa akses anak sendiri
        if ($student->parent_id !== Auth::id()) {
            abort(403);
        }
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
    

    // Form daftar ulang
    public function reapply(Student $student)
    {
        $fee = Fee::first();
        if ($student->status !== 'ditolak') {
            abort(403);
        }

        return view('students.reapply', compact('student', 'fee'));
    }


    // Kirim data form daftar ulang
    public function submitReapply(Request $request, Student $student)
    { 
        if ($student->status !== 'ditolak') {
            abort(403);
        }

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
        $kkPath = $student->kk_file;

        if ($request->hasFile('kk_file')) {
            if ($student->kk_file) {
                Storage::disk('public')->delete($student->kk_file);
            }
            $kkPath = $request->file('kk_file')->store('kk', 'public');
        }

        // HANDLE FILE AKTA
        $aktaPath = $student->birth_certificate_file;

        if ($request->hasFile('birth_certificate_file')) {
            if ($student->birth_certificate_file) {
                Storage::disk('public')->delete($student->birth_certificate_file);
            }
            $aktaPath = $request->file('birth_certificate_file')->store('akta', 'public');
        }

        // UPDATE DATA SISWA
        $student->update([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'school_origin' => $request->school_origin,
            'status' => 'nonaktif', // balik ke pending
            'approved_at' => null,
            'kk_file' => $kkPath,
            'birth_certificate_file' => $aktaPath,
            'reject_reason' => null
        ]);

        // HANDLE PAYMENT REGISTRATION
        $payment = Payment::where('student_id', $student->id)
            ->where('type', 'registration')
            ->first();

        if ($payment) {

            // jika upload bukti tf baru
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

                // Kirim notifikasi ke superadmin
                $superadmins = User::role('superadmin')->get();
                
                foreach ($superadmins as $admin) {
                    $admin->notify(
                        new PaymentUploadedNotification($payment)
                    );
                }

            } else {

                // Tidak upload baru tetap pakai lama
                $payment->update([
                    'status' => 'pending'
                    // proof_file tetap
                    // paid_at tetap
                ]);

                // Kirim notifikasi ke superadmin
                $superadmins = User::role('superadmin')->get();
                
                foreach ($superadmins as $admin) {
                    $admin->notify(
                        new PaymentUploadedNotification($payment)
                    );
                }
            }

        } else {

            // jika tidak upload bukti tf baru
            if ($request->hasFile('payment_proof')) {

                $newProof = $request->file('payment_proof')->store('payments', 'public');

                // Tahun akademik
                $academicYear = AcademicYear::where('is_active', true)->first();
                if (!$academicYear) {
                    return back()->with('error', 'Tahun ajaran aktif belum tersedia');
                }

                $payment = Payment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'type' => 'registration',
                    'original_amount' => 100000,
                    'amount' => 100000,
                    'proof_file' => $newProof,
                    'status' => 'pending',
                    'paid_at' => now()
                ]);

                // Kirim notifikasi ke superadmin
                $superadmins = User::role('superadmin')->get();
                
                foreach ($superadmins as $admin) {
                    $admin->notify(
                        new PaymentUploadedNotification($payment)
                    );
                }
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran ulang berhasil, menunggu persetujuan');
    }
}
