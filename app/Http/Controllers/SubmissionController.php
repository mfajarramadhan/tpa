<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index(Request $request, Material $material)
    {
        // ambil semua siswa di kelas materi ini
        $classroom = $material->subject->classroom;

        $students = Student::where('classroom_id', $classroom->id)->get();

        // ambil semua submission untuk materi ini
        $submissions = $material->submissions()
        ->with('student')
        ->latest()
        ->get()
        ->keyBy('student_id');

        // SEARCH
        if ($request->filled('search')) {
            $students = $students->filter(function ($student) use ($request) {
                return str_contains(
                    strtolower($student->name),
                    strtolower($request->search)
                );
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {

            $students = $students->filter(function ($student) use ($request, $submissions) {

                $submission = $submissions[$student->id] ?? null;

                if ($request->status === 'belum') {
                    return !$submission;
                }

                if (!$submission) return false;

                return $submission->status === $request->status;
            });
        }

        return view('submissions.index', compact('material', 'students', 'submissions'));
    }

    public function create(Material $material)
    {
        return view('submissions.create', compact('material'));
    }

    public function store(Request $request, Material $material)
    {
        $request->validate([
            // 'material_id' => 'required' tidak perlu, sudah pakai route model binding
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'link' => 'nullable|url'
        ]);

        // minimal salah satu
        if (!$request->hasFile('file') && !$request->filled('link')) {
            return back()->withErrors([
                'file' => 'Upload file atau isi link'
            ]);
        }

        // tidak boleh dua-duanya
        if ($request->hasFile('file') && $request->filled('link')) {
            return back()->withErrors([
                'file' => 'Pilih salah satu: file atau link'
            ]);
        }

        $path = null;
        $link = null;

        // ambil submission lama (jika ada)
        $oldSubmission = Submission::where('material_id', $material->id)
            ->where('student_id', auth()->user()->student->id)
            ->first();

        // FILE → file_path
        if ($request->hasFile('file')) {

            // hapus file lama jika ada
            if ($oldSubmission && $oldSubmission->file_path) {
                Storage::disk('public')->delete($oldSubmission->file_path);
            }

            $path = $request->file('file')->store('submissions', 'public');
        }

        // LINK → link
        if ($request->filled('link')) {

            // hapus file lama jika sebelumnya file
            if ($oldSubmission && $oldSubmission->file_path) {
                Storage::disk('public')->delete($oldSubmission->file_path);
            }

            $link = $request->link;
        }

        // update atau create
        Submission::updateOrCreate(
            [
                'material_id' => $material->id,
                'student_id' => auth()->user()->student->id,
            ],
            [
                'file_path' => $path,
                'link' => $link,
                'status' => 'terkirim'
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

        return back()->with('success', 'Tugas selesai');
    }


    public function revise(Request $request, Submission $submission)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $submission->update([
            'status' => 'perbaiki',
            'note' => $request->note
        ]);

        return back()->with('success', 'Tugas dikembalikan untuk diperbaiki!');
    }
}