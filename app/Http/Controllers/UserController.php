<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 filter nama
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->with('roles')->get();

        return view('users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role berhasil diubah');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status == 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return back()->with('success', 'Status diubah');
    }
}
