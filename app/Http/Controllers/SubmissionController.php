<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function create(Material $material)
    {
        return view('submissions.create', compact('material'));
    }

    public function store(Request $request, Material $material)
    {
        $request->validate([
            'material_id' => 'required',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'link' => 'nullable|url'
        ]);

        // VALIDASI MINIMAL SALAH SATU
        if (!$request->hasFile('file') && !$request->filled('link')) {
            return back()->withErrors([
                'file' => 'Upload file atau isi link. Tidak boleh keduanya!'
            ]);
        }

        $path = null;

        // FILE → file_path
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
        }

        // LINK → link
        Submission::updateOrCreate(
        [
            'material_id' => $material->id,
            'student_id' => auth()->user()->student->id,
        ],
        [
            'file_path' => $path,
            'link' => $request->link,
        ]
    );

        return redirect()->route('learning.subject', $material->subject_id)
            ->with('success', 'Tugas berhasil diupload');
    }

    public function destroy(Submission $submission)
    {
        // hapus file
        Storage::disk('public')->delete($submission->file_path);

        $submission->delete();

        return back()->with('success', 'Tugas dihapus');
    }

    public function complete(Submission $submission)
    {
        $submission->update([
            'status' => 'selesai'
        ]);

        return back()->with('success', 'Tugas ditandai selesai');
    }
}