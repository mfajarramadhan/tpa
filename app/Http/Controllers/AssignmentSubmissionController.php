<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmissionController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,jpg,png|max:2048'
        ]);

        $student = Auth::user()->student;

        $filePath = $request->file('file')->store('submissions', 'public');

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $id,
                'student_id' => $student->id
            ],
            [
                'file_path' => $filePath
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan');
    }

    public function destroy($id)
    {
        $submission = AssignmentSubmission::findOrFail($id);

        if ($submission->student_id !== Auth::user()->student->id) {
            abort(403);
        }

        $submission->delete();

        return back()->with('success', 'Tugas berhasil dihapus');
    }
}
