<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * 🔹 STEP 1: LIST KELAS (seperti absensi)
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('guru') || $user->hasRole('superadmin')) {
            $classrooms = $classrooms = Classroom::withCount('subjects')->get();

        } elseif ($user->hasRole('siswa')) {
            $classrooms = Classroom::where('id', $user->student->classroom_id)
                ->withCount('subjects')
                ->get();

        } elseif ($user->hasRole('orang_tua')) {
            $classrooms = Classroom::whereIn(
                    'id',
                    $user->students->pluck('classroom_id')
                )
                ->withCount('subjects')
                ->get();
        } else {
            $classrooms = collect();
        }

        return view('learning.index', compact('classrooms'));
    }


    /**
     * 🔹 STEP 2: LIST MAPEL PER KELAS
     */
    public function classroom(Classroom $classroom)
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

    public function create(Classroom $classroom)
    {
        return view('learning.create', compact('classroom'));
    }

    public function show(Subject $subject)
    {
        $subject->load([
            'materials' => function ($q) {
                $q->latest();
            },
            'materials.user',
            'materials.submissions.student'
        ]);

        return view('learning.show', compact('subject'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'day' => 'required|integer|min:1|max:7'
        ]);

        Subject::create([
            'classroom_id' => $classroom->id,
            'name' => $request->name,
            'day' => $request->day
        ]);

        return redirect()
            ->route('learning.classroom', $classroom->id)
            ->with('success', 'Mapel berhasil ditambahkan');
    }

    public function edit(Subject $subject)
    {
        return view('learning.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'day' => 'required|integer|min:1|max:7'
        ]);

        $subject->update([
            'name' => $request->name,
            'day' => $request->day
        ]);

        return redirect()
            ->route('learning.classroom', $subject->classroom_id)
            ->with('success', 'Mapel berhasil diperbarui');
    }

    public function destroy(Subject $subject)
    {
        $classroomId = $subject->classroom_id;

        $subject->delete();

        return redirect()
            ->route('learning.classroom', $classroomId)
            ->with('success', 'Mapel berhasil dihapus');
    }
}