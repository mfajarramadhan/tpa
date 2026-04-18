<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string',
            'nik' => 'required|digits:16|unique:students,nik',
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->age < 2) {
                        $fail('Usia anak minimal 2 tahun');
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

        // Generate email unik siswa
        // ambil nama (tanpa spasi)
        $name = strtolower(str_replace(' ', '', $request->name));

        // format tanggal lahir (ddmmyyyy)
        $birth = Carbon::parse($request->birth_date)->format('dmY');

        // gabungkan nama + tanggal lahir
        $email = $name . $birth . '@gmail.com';

        // CEK DUPLIKAT (WAJIB)
        if (User::where('email', $email)->exists()) {
            $email = $name . $birth . rand(10,99) . '@gmail.com';
        }

        // Generate password dari tanggal lahir
        $password = Hash::make($request->birth_date);

        // Buat akun siswa
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => $password,
            'status' => 'nonaktif',
            'approval_status' => 'approved'
        ]);

        $user->assignRole('siswa');

        // Simpan data siswa
        $student = Student::create([
            'parent_id' => Auth::user()->id,
            'user_id' => $user->id,
            'classroom_id' => null,
            'nik' => $request->nik,
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

        return redirect()->route('students.index')
            ->with('success', 'Data anak berhasil ditambahkan, menunggu approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $this->authorizeStudent($student);

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
        $this->authorizeStudent($student);

        $request->validate([
            'name' => 'required',
            'address' => 'required'
        ]);

        $student->update($request->only('name', 'address'));

        return redirect()->route('students.index')->with('success', 'Data berhasil diupdate');
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
}
