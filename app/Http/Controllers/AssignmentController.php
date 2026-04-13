<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('siswa')) {
            $student = $user->student;

            $assignments = Assignment::where('classroom_id', $student->classroom_id)
                ->with('classroom')
                ->get();
        } else {
            $assignments = Assignment::with('classroom')->get();
        }

        $submittedAssignments = [];

        if (Auth::user()->hasRole('siswa')) {
            $student = Auth::user()->student;

            $submittedAssignments = AssignmentSubmission::where('student_id', $student->id)
                ->pluck('assignment_id')
                ->toArray();
        }

        return view('assignments.index', compact('assignments', 'submittedAssignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Classroom::all();

        return view('assignments.create', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'title' => 'required',
            'description' => 'nullable',
            'deadline' => 'required|date',
            'file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $filePath = null;

        if ($request->file('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'classroom_id' => $request->classroom_id,
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'file_path' => $filePath,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        //
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $assignment = Assignment::findOrFail($id);

        $student = Auth::user()->student;

        // 🔥 upload file
        $path = $request->file('file')->store('submissions', 'public');

        // 🔥 INI YANG KAMU TANYA
        Submission::updateOrCreate(
            [
                'student_id' => $student->id,
                'assignment_id' => $assignment->id
            ],
            [
                'file_path' => $path
            ]
        );

        return back()->with('success', 'Tugas berhasil diupload');
    }
}
