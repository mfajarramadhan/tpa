<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- ================= TAMBAH MATERI ================= --}}
            @role('guru|superadmin')
            <div class="flex justify-end">

                <a href="{{ route('materials.create', $subject->id) }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">

                    + Tambah Materi

                </a>

            </div>
            @endrole

            @if($subject->materials->isEmpty())
                <div class="p-6 text-center text-gray-500 bg-white rounded">
                    Belum ada materi
                </div>
            @endif

            {{-- ================= MATERI ================= --}}
            @foreach($subject->materials as $material)
                <div class="p-6 bg-white shadow rounded-xl">

                    {{-- TITLE --}}
                    <h3 class="text-lg font-bold">
                        {{ $material->title }}
                    </h3>

                    {{-- DESC --}}
                    <p class="mt-2 text-sm text-gray-600">
                        {{ $material->description }}
                    </p>

                    {{-- PREVIEW --}}
                    <div class="mt-4">

                        {{-- YOUTUBE --}}
                        @if($material->youtube_link)
                            <div class="aspect-video">
                                <iframe
                                    src="{{ str_replace('watch?v=', 'embed/', $material->youtube_link) }}"
                                    class="w-full h-full rounded-lg"
                                    allowfullscreen>
                                </iframe>
                            </div>

                        {{-- FILE --}}
                        @elseif($material->file_path)
                            <a href="{{ asset('storage/' . $material->file_path) }}"
                            target="_blank"
                            class="text-blue-600 underline">
                                Lihat File Materi
                            </a>
                        @endif

                    </div>

                    {{-- ================= TUGAS ================= --}}
                    @if($material->is_task)

                        {{-- SUBMISSION LIST --}}
                        <div class="mt-4 space-y-2">

                            @foreach($material->submissions as $submission)
                            <div class="flex justify-between p-2 bg-gray-100 rounded">

                                <span>{{ $submission->student->name }}</span>

                                <a href="{{ asset('storage/' . $submission->file_path) }}"
                                target="_blank"
                                class="text-xs text-blue-600 underline">
                                    Lihat
                                </a>

                            </div>
                            @endforeach

                        </div>

                        {{-- BUTTON --}}
                        @if(auth()->user()->hasRole('siswa') || auth()->user()->hasRole('superadmin'))
                        <div class="mt-4">
                            <a href="{{ route('submissions.create', $material->id) }}"
                            class="block w-full py-2 text-center text-white bg-blue-600 rounded">

                                Upload Tugas

                            </a>
                        </div>
                        @endif

                    @endif

                </div>
                @endforeach

        </div>
    </div>
</x-app-layout>