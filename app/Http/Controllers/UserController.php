<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // 🔷 LIST USER + FILTER
    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id());

        // 🔍 filter nama
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->role) {
        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('name', $request->role);
        });
    }
        $users = $query->with('roles')->latest()->get();

        return view('users.index', compact('users'));
    }

    // 🔷 BUAT USER
    public function create()
    {
        return view('users.create');
    }

    // 🔷 SIMPAN USER
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        // \larang buat siswa manual
        if ($request->role == 'siswa') {
            return back()->with('error', 'Akun siswa dibuat otomatis dari data anak');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'aktif',
            'approval_status' => 'approved'
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat');
    }

    // 🔷 DETAIL USER
    public function show($id)
    {
        $user = User::with(['student.classroom'])->findOrFail($id);

        return view('users.show', compact('user'));
    }

    // 🔷 FORM EDIT USER
    public function edit($id)
    {
        $user = User::with('student')->findOrFail($id);

        $classrooms = Classroom::all();

        return view('users.edit', compact('user', 'classrooms'));
    }

    // 🔷 UPDATE DATA USER
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        // PROTEKSI SUPERADMIN
        if ($user->hasRole('superadmin') && auth()->id() != $user->id) {
            return back()->with('error', 'Tidak bisa mengubah superadmin lain');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ];

        // PASSWORD OPTIONAL
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        if ($user->student) {
            $user->student->update([
                'classroom_id' => $request->classroom_id,
                'nik' => $request->nik,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'school_origin' => $request->school_origin
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    // 🔷 UPDATE ROLE (CEPAT - DROPDOWN)
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $currentRole = $user->roles->first()->name;
        $newRole = $request->role;

        // LARANG JADI SISWA
        if ($newRole == 'siswa') {
            return back()->with('error', 'Role siswa tidak bisa di-set manual');
        }

        // SUPERADMIN TIDAK BOLEH DIUBAH
        if ($currentRole == 'superadmin') {
            return back()->with('error', 'Role superadmin tidak boleh diubah');
        }

        $user->syncRoles([$newRole]);

        return back()->with('success', 'Role berhasil diubah');
    }

    // 🔷 TOGGLE STATUS
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status == 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return back()->with('success', 'Status diubah');
    }

    // 🔷 DELETE USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // jangan sampai hapus diri sendiri
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
