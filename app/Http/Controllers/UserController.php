<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // 🔷 LIST USER + FILTER
    public function index(Request $request)
    {
        $status = $request->has('status') ? $request->status : 'active';

        $query = User::withTrashed()
            ->where('id', '!=', auth()->id());

        // 🔍 filter nama
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 🔍 filter role
        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $status = request('status', 'aktif'); // DEFAULT AKTIF

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif ($status === 'aktif') {
            $query->where('status', 'aktif')->whereNull('deleted_at');
        } elseif ($status === 'nonaktif') {
            $query->where('status', 'nonaktif')->whereNull('deleted_at');
        }

        $users = $query->with('roles')->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users', 'status'));
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
            'phone' => ['required', 'regex:/^08[0-9]{8,11}$/', 'unique:users,phone'], //diawali 08, setelah 08 hanya boleh angka, jumlah angka setelah 08 antara 8 sampai 11 digit (total 10-13 digit)
            'email' => ['required', 'email', 'unique:users,email'],
            'address' => 'nullable|string',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        // larang siswa buat akun manual
        if ($request->role == 'siswa') {
            return back()->with(
                'error', 
                'Akun siswa dibuat otomatis dari akun orangtua!');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'aktif',
            'approval_status' => 'approved',
            'role' => $request->role
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with(
            'success', 
            'Berhasil menambahkan user baru!');
    }

    // 🔷 DETAIL USER
    public function show(User $user)
    {
        $user->load(['student.classroom']);

        return view('users.show', compact('user'));
    }

    // 🔷 FORM EDIT USER
    public function edit(User $user)
    {
        $user->load('student');

        $classrooms = Classroom::all();

        return view('users.edit', compact('user', 'classrooms'));
    }

    // 🔷 UPDATE DATA USER
    public function update(Request $request, User $user)
    {
        $request->validate([

        // USER
        'name' => 'required|string|max:255',

        'phone' => [
            'required',
            'regex:/^08[0-9]{8,11}$/', //diawali 08, setelah 08 hanya boleh angka, jumlah angka setelah 08 antara 8 sampai 11 digit (total 10-13 digit)
            'unique:users,phone,' . $user->id
        ],

        'email' => [
            'required',
            'email',
            'unique:users,email,' . $user->id
        ],

        'address' => 'nullable|string|max:255',

        'password' => 'nullable|min:6',

        // STUDENT
        'classroom_id' => 'nullable|exists:classrooms,id',

        'nisn' => [
            'nullable',
            'digits:10',
            'unique:students,nisn,' . optional($user->student)->id
        ],

        'birth_date' => [
            'nullable',
            'date',
            function ($attribute, $value, $fail) {

                if ($value && Carbon::parse($value)->age < 8) {

                    $fail('Usia anak minimal 8 tahun!');

                }

            }
        ],

        'gender' => 'nullable|in:L,P',

        'school_origin' => 'nullable|string|max:255',

        'school_grade' => 'nullable|string|max:20',

    ]);

        // PROTEKSI SUPERADMIN
        if ($user->hasRole('superadmin') && auth()->id() != $user->id) {

            return back()->with(
                'error',
                'Tidak bisa mengubah superadmin lain'
            );
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ];

        // PASSWORD OPTIONAL
        if ($request->filled('password')) {

            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        /*
        =====================================================
        UPDATE STUDENT
        =====================================================
        */
        if ($user->student) {

            $user->student->update([

                'classroom_id' => $request->classroom_id,
                'nisn' => $request->nisn,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'school_origin' => $request->school_origin,
                'school_grade' => $request->school_grade

            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Data user berhasil diperbarui!');
    }

    // 🔷 UPDATE ROLE (CEPAT - DROPDOWN)
    public function updateRole(Request $request, User $user)
    {
        $currentRole = $user->roles->first()->name;

        $newRole = $request->role;

        // LARANG JADI SISWA
        if ($newRole == 'siswa') {

            return back()->with(
                'error',
                'Role siswa tidak bisa di-set manual'
            );
        }

        // SUPERADMIN TIDAK BOLEH DIUBAH
        if ($currentRole == 'superadmin') {

            return back()->with(
                'error',
                'Role superadmin tidak boleh diubah'
            );
        }

        $user->syncRoles([$newRole]);

        return back()->with(
            'success',
            'Role berhasil diubah'
        );
    }

    // 🔷 TOGGLE STATUS
    public function toggleStatus(User $user)
    {
        $user->status =
            $user->status == 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $user->save();

        return back()->with(
            'success',
            'Status diubah'
        );
    }

    // 🔷 DELETE USER
    public function destroy(User $user)
    {
        // jangan sampai hapus diri sendiri
        if ($user->id == auth()->id()) {

            return back()->with(
                'error',
                'Tidak bisa menghapus akun sendiri'
            );
        }

        // ubah status jadi nonaktif
        $user->update([
            'status' => 'nonaktif'
        ]);

        // soft delete
        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus'
        );
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()
            ->findOrFail($id);

        $user->forceDelete();

        return back()->with(
            'success',
            'User dihapus permanen'
        );
    }

    public function restore($id)
    {
        $user = User::withTrashed()
            ->findOrFail($id);

        $user->restore();

        return back()->with(
            'success',
            'User berhasil dipulihkan!'
        );
    }
}
