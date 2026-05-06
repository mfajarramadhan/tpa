<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Materi</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto card-panel">

            <form method="POST"
                  action="{{ route('materials.update', $material->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- JUDUL --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Judul</label>
                    <input type="text" name="title"
                           value="{{ $material->title }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                           required>
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Deskripsi</label>
                    <textarea name="description" rows="5"
                              class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">{{ $material->description }}</textarea>
                </div>

                {{-- PILIH TIPE --}}
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-semibold">Jenis Materi</label>

                    @php
                        $type = $material->youtube_link ? 'youtube' : 'file';
                    @endphp

                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="file"
                                   {{ $type === 'file' ? 'checked' : '' }}>
                            Upload File
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="youtube"
                                   {{ $type === 'youtube' ? 'checked' : '' }}>
                            YouTube
                        </label>
                    </div>
                </div>

                {{-- ================= FILE LAMA ================= --}}
                @if($material->file_path)

                    @php
                        $fileUrl = asset('storage/' . $material->file_path);
                        $ext = strtolower(pathinfo($material->file_path, PATHINFO_EXTENSION));
                    @endphp

                    <div class="mb-3">

                        <p class="mb-1 text-sm font-semibold">File Saat Ini</p>

                        {{-- IMAGE --}}
                        @if(in_array($ext, ['jpg','jpeg','png']))
                            <img src="{{ $fileUrl }}" class="w-40 rounded shadow">

                        {{-- PDF --}}
                        @elseif($ext === 'pdf')
                            <iframe src="{{ $fileUrl }}"
                                    class="w-full h-[300px] rounded border"></iframe>

                        {{-- FALLBACK --}}
                        @else
                            <a href="{{ $fileUrl }}" target="_blank"
                            class="text-blue-600 underline">
                                Lihat File
                            </a>
                        @endif

                    </div>

                @endif

                {{-- FILE --}}
                <div class="mb-4" id="fileWrapper">
                    <label class="block mb-1 text-sm font-semibold">File</label>
                    <input type="file" name="file" id="fileInput"
                           class="w-full p-2 border rounded-lg">
                </div>

                {{-- YOUTUBE --}}
                <div class="mb-4" id="youtubeWrapper">
                    <label class="block mb-1 text-sm font-semibold">YouTube ID</label>
                    <input type="text" name="youtube_link" id="youtubeInput"
                           value="{{ $material->youtube_link }}"
                           placeholder="Contoh: rzbjF2DRv1c"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    <p class="mt-1 text-xs text-gray-500">
                        Masukkan kode unik YouTube (ID), bukan link utuh!<br>
                        Contoh: https://youtu.be/rzbjF2DRv1c → <b>rzbjF2DRv1c</b>
                    </p>
                </div>

                {{-- TASK --}}
                <div class="mb-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_task"
                               {{ $material->is_task ? 'checked' : '' }}
                               class="rounded">
                        Jadikan sebagai tugas
                    </label>
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Update
                </button>

            </form>
        </div>
    </div>
</x-app-layout>

<script>
    const radios = document.querySelectorAll('input[name="type"]');
    const fileWrapper = document.getElementById('fileWrapper');
    const youtubeWrapper = document.getElementById('youtubeWrapper');

    function toggleInput(type) {
        if (type === 'file') {
            fileWrapper.classList.remove('hidden');
            youtubeWrapper.classList.add('hidden');
        } else {
            fileWrapper.classList.add('hidden');
            youtubeWrapper.classList.remove('hidden');
        }
    }

    // set initial state
    const checked = document.querySelector('input[name="type"]:checked');
    if (checked) {
        toggleInput(checked.value);
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            toggleInput(this.value);
        });
    });
</script>