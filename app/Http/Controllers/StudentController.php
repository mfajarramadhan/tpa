<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        $request->validate([
            'name' => 'required|string',
            'nik' => 'required|unique:students,nik',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required',
            'kk_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'birth_certificate_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 🔥 Upload file
        $kkPath = $request->file('kk_file')->store('kk', 'public');
        $aktaPath = $request->file('birth_certificate_file')->store('akta', 'public');

        // 🔥 Generate email unik siswa
        $baseEmail = Str::slug($request->name);
        $email = $baseEmail . rand(100,999) . '@tpadta.com';

        // 🔥 Generate password dari tanggal lahir
        $password = Hash::make($request->birth_date);

        // 🔥 Buat akun siswa
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => $password,
            'status' => 'approved'
        ]);

        $user->assignRole('siswa');

        // 🔥 Simpan data siswa
        Student::create([
            'parent_id' => Auth::user()->id,
            'user_id' => $user->id,
            'classroom_id' => null,
            'nik' => $request->nik,
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'kk_file' => $kkPath,
            'birth_certificate_file' => $aktaPath,
            'status' => 'nonaktif' // menunggu approval
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
