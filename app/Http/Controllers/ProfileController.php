<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // siswa hanya boleh ganti password
        if ($user->student) {

            $request->validate([
                'password' => 'nullable|min:6|confirmed'
            ]);

            $data = [];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

        } else {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'address' => 'nullable|string|max:255',
                'password' => 'nullable|min:6|confirmed'
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
            ];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
        }

        $user->update($data);

        return back()->with(
            'success',
            'Profile berhasil diperbarui!'
        );
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
