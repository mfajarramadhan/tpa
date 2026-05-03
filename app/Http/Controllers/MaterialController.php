<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function create(Subject $subject)
    {
        return view('materials.create', compact('subject'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'subject_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'file' => 'nullable|file|max:2048',
            'youtube_link' => 'nullable|string'
        ]);

        $filePath = null;

        if ($request->file('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        Material::create([
            'subject_id' => $request->subject_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'youtube_link' => $request->youtube_link,
            'is_task' => $request->has('is_task'),
        ]);

        return redirect()->route('learning.subject', $request->subject_id)
            ->with('success', 'Materi berhasil ditambahkan');
    }
}
