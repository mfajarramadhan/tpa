<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tugas</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- BUTTON BUAT TUGAS (GURU) --}}
            @if(Auth::user()->hasRole('guru') || Auth::user()->hasRole('superadmin'))
                <div class="mb-4">
                    <a href="{{ route('assignments.create') }}"
                       class="px-4 py-2 text-white transition bg-blue-600 rounded hover:bg-blue-700">
                        + Buat Tugas
                    </a>
                </div>
            @endif

            {{-- LIST TUGAS --}}
            @forelse($assignments as $assignment)
                <div class="p-5 mb-4 transition bg-white rounded-lg shadow hover:shadow-lg">

                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold">{{ $assignment->title }}</h3>

                        {{-- DEADLINE BADGE --}}
                        <span class="text-sm px-2 py-1 rounded text-white
                            {{ \Carbon\Carbon::parse($assignment->deadline)->isPast() ? 'bg-red-500' : 'bg-green-500' }}">
                            Deadline: {{ $assignment->deadline }}
                        </span>
                    </div>

                    <p class="mb-2 text-gray-600">
                        {{ $assignment->description }}
                    </p>

                    {{-- FILE MATERI --}}
                    @if($assignment->file_path)
                        <a href="{{ asset('storage/' . $assignment->file_path) }}"
                           target="_blank"
                           class="text-sm text-blue-600 underline">
                            Lihat File
                        </a>
                    @endif

                    {{-- 🔥 STATUS + FORM SISWA --}}
                    @if(Auth::user()->hasRole('siswa'))

                        <div class="mt-3">

                            {{-- STATUS --}}
                            @if(in_array($assignment->id, $submittedAssignments))
                                <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">
                                    Sudah Upload
                                </span>

                                <p class="mt-1 text-xs text-gray-500">
                                    *Upload ulang akan mengganti file sebelumnya
                                </p>
                            @else
                                <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">
                                    Belum Upload
                                </span>
                            @endif

                            {{-- FORM UPLOAD (SELALU ADA) --}}
                            <form method="POST"
                                  action="{{ route('assignments.submit', $assignment->id) }}"
                                  enctype="multipart/form-data"
                                  class="flex items-center gap-3 mt-3">
                                @csrf

                                <input type="file"
                                       name="file"
                                       required
                                       class="p-2 text-sm border rounded">

                                <button
                                    onclick="this.disabled=true;this.form.submit();"
                                    class="px-4 py-2 text-white transition bg-green-600 rounded hover:bg-green-700">

                                    @if(in_array($assignment->id, $submittedAssignments))
                                        Upload Ulang
                                    @else
                                        Upload
                                    @endif

                                </button>
                            </form>

                        </div>

                    @endif

                </div>
            @empty
                <div class="text-center text-gray-500">
                    Belum ada tugas
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>