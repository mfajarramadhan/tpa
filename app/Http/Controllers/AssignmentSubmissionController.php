<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmissionController extends Controller
{
    public function create(Assignment $assignment)
    {
        return view('submissions.create', compact('assignment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required',
            'file' => 'required|file|max:2048'
        ]);

        $path = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::create([
            'assignment_id' => $request->assignment_id,
            'student_id' => auth()->user()->student->id,
            'file_path' => $path,
        ]);

        return redirect()->route('learning.subject', $request->assignment_id)
            ->with('success', 'Tugas berhasil diupload');
    }
}
