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
    


    

    public function create(Classroom $classroom)
    {
        return view('learning.subject-create', compact('classroom'));
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
        return view('learning.subject-edit', compact('subject'));
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