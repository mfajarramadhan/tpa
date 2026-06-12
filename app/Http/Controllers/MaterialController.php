<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\MaterialUploadedNotification;

class MaterialController extends Controller
{
    public function create(Subject $subject)
    {
        return view('materials.create', compact('subject'));
    }

    public function extractYoutubeId(?string $input): ?string
    {
        if (!$input) return null;

        $input = trim($input);

        // Kalau user sudah input ID (11 char, aman)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        // Coba parse URL
        $url = parse_url($input);
        if (!$url || !isset($url['host'])) return null;

        $host = strtolower($url['host']);

        // youtu.be/VIDEO_ID
        if (str_contains($host, 'youtu.be')) {
            $path = trim($url['path'] ?? '', '/');
            $id = explode('/', $path)[0] ?? null;
            return (preg_match('/^[a-zA-Z0-9_-]{11}$/', $id)) ? $id : null;
        }

        // youtube.com/watch?v=VIDEO_ID
        if (str_contains($host, 'youtube.com') || str_contains($host, 'm.youtube.com')) {
            // /watch?v=...
            if (isset($url['query'])) {
                parse_str($url['query'], $q);
                if (!empty($q['v']) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $q['v'])) {
                    return $q['v'];
                }
            }

            // /shorts/VIDEO_ID atau /embed/VIDEO_ID
            $path = trim($url['path'] ?? '', '/');
            $segments = explode('/', $path);

            foreach (['shorts', 'embed'] as $type) {
                $idx = array_search($type, $segments);
                if ($idx !== false && isset($segments[$idx + 1])) {
                    $id = $segments[$idx + 1];
                    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $id)) {
                        return $id;
                    }
                }
            }
        }

        return null;
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'subject_id' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'youtube_link' => 'nullable|string|max:255',
        ]);

        $youtubeId = $this->extractYoutubeId($request->youtube_link);

        $filePath = null;

        if ($request->file('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        $material = Material::create([
            'subject_id' => $request->subject_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'youtube_link' => $youtubeId,
            'is_task' => $request->has('is_task'),
        ]);

        // Filter sebelum kirim notifikasi 
        // Classroom material
        $classroomId = $material->subject->classroom_id;

        // Siswa sesuai kelas
        $students = User::role('siswa')
            ->whereHas('student', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            })
            ->get();

        // Orang tua siswa di kelas tersebut
        $parents = User::role('orang_tua')
            ->whereHas('students', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            })
            ->get();

        // Merge penerima notif
        $receivers = $students->merge($parents);

        // Kirim notifikasi ke orangtua & siswa
        foreach ($receivers as $user) {

            $user->notify(
                new MaterialUploadedNotification($material)
            );

        }

        return redirect()->route('learning.subject', $request->subject_id)->with('success',
            $material->is_task
                ? 'Berhasil menambahkan tugas baru!'
                : 'Berhasil menambahkan materi baru!'
        );
    }

    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|max:100',
            'description' => 'nullable',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'youtube_link' => 'nullable|string'
        ]);

        // jangan isi dua-duanya
        if ($request->hasFile('file') && $request->filled('youtube_link')) {
            return back()->withErrors([
                'file' => 'Pilih salah satu: upload file atau link YouTube'
            ])->withInput();
        }

        $filePath = $material->file_path;
        $youtube = $material->youtube_link;

        // kalau upload file baru
        if ($request->hasFile('file')) {

            // hapus file lama
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }

            $filePath = $request->file('file')->store('materials', 'public');
            $youtube = null; // reset link
        }

        // kalau isi youtube baru
        if ($request->filled('youtube_link')) {
            $youtube = $this->extractYoutubeId($request->youtube_link);

            // hapus file lama kalau ada
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }

            $filePath = null;
        }

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'youtube_link' => $youtube,
            'is_task' => $request->has('is_task')
        ]);

        return redirect()
            ->route('learning.subject', $material->subject_id)
            ->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy(Material $material)
    {
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus');
    }
    
}
