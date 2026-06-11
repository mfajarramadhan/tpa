<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    // List Kelas
    public function index()
    {
        $user = auth()->user();

        // SUPERADMIN & GURU
        if (
            $user->hasRole('guru') ||
            $user->hasRole('superadmin')
        ) {
            $classrooms = Classroom::withCount('subjects')->get();

        // SISWA
        } elseif ($user->hasRole('siswa')) {

            $classrooms = Classroom::where(
                    'id',
                    $user->student->classroom_id
                )->withCount('subjects')->get();

        // ORANG TUA
        } elseif ($user->hasRole('orang_tua')) {

            $classrooms = Classroom::whereIn(
                    'id',
                    $user->students
                        ->pluck('classroom_id')
                )->withCount('subjects')->get();
        } else {
            $classrooms = collect();
        }

        return view(
            'learning.index',
            compact('classrooms')
        );
    }


    public function create()
    {
        return view('learning.classroom-create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50'
        ]);

        Classroom::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('learning.index')
            ->with(
                'success',
                'Kelas berhasil ditambahkan!'
            );
    }


    // List Mapel
    public function show(Classroom $classroom)
    {
        $classroom->load([
            'subjects' => function ($q) {
                $q->withCount([

                // total materi
                'materials as materials_count' => function ($query) {
                    $query->where('is_task', false);
                },

                // total tugas
                'materials as tasks_count' => function ($query) {
                    $query->where('is_task', true);
                }

            ])->orderBy('day');
            }
        ]);

        return view('learning.classroom', compact('classroom'));
    }


    public function edit(Classroom $classroom)
    {
        return view('learning.classroom-edit', compact('classroom'));
    }


    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|max:50'
        ]);

        $classroom->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('learning.index')
            ->with(
                'success',
                'Kelas berhasil diperbarui!'
            );
    }


    public function destroy(Classroom $classroom)
    {
        // CEK SISWA
        if ($classroom->students()->count()) {

            return back()->with(
                'error',
                'Kelas masih memiliki siswa!'
            );
        }

        // CEK SUBJECT
        if ($classroom->subjects()->count()) {

            return back()->with(
                'error',
                'Kelas masih memiliki mata pelajaran!'
            );
        }

        $classroom->delete();

        return back()->with(
            'success',
            'Kelas berhasil dihapus!'
        );
    }

    // Lihat semua anggota kelas
    public function members(Classroom $classroom)
    {
        $user = auth()->user();

        // SISWA
        if (
            $user->hasRole('siswa') &&
            $user->student->classroom_id != $classroom->id
        ) {
            abort(403);
        }

        // ORANG TUA
        if (
            $user->hasRole('orang_tua') &&
            !$user->students
                ->pluck('classroom_id')
                ->contains($classroom->id)
        ) {
            abort(403);
        }

        $teachers = User::role('guru')
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $students = $classroom->students()
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view(
            'learning.members',
            compact(
                'classroom',
                'teachers',
                'students'
            )
        );
    }
}
