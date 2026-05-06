<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah Materi</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto card-panel">

            <form method="POST"
                  action="{{ route('materials.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                {{-- JUDUL --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Judul</label>
                    <input type="text" name="title"
                           value="{{ old('title') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Deskripsi</label>
                    <textarea name="description" rows="5"
                              class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                </div>

                {{-- PILIH TIPE --}}
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-semibold">Jenis Materi</label>

                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="file" checked>
                            Upload File
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="youtube">
                            YouTube
                        </label>
                    </div>
                </div>

                {{-- FILE --}}
                <div class="mb-4" id="fileWrapper">
                    <label class="block mb-1 text-sm font-semibold">File</label>
                    <input type="file" name="file" id="fileInput"
                           class="w-full p-2 border rounded-lg">
                    <p class="mt-1 text-xs text-gray-500">
                        Hanya PDF/JPG/PNG/JPEG
                    </p>
                </div>

                {{-- YOUTUBE --}}
                <div class="hidden mb-4" id="youtubeWrapper">
                    <label class="block mb-1 text-sm font-semibold">YouTube ID</label>
                    <input type="text" name="youtube_link" id="youtubeInput"
                           placeholder="Contoh: rzbjF2DRv1c"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    <p class="mt-1 text-xs text-gray-500">
                        Masukkan kode unik link YouTube, bukan link utuh!<br>
                        Contoh: https://youtu.be/rzbjF2DRv1c → <b>rzbjF2DRv1c</b>
                    </p>
                </div>

                {{-- TASK --}}
                <div class="mb-4">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_task" value="1" class="rounded">
                        Jadikan sebagai tugas
                    </label>
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Simpan
                </button>

            </form>
        </div>
    </div>
</x-app-layout>

<script>
    const radios = document.querySelectorAll('input[name="type"]');
    const fileWrapper = document.getElementById('fileWrapper');
    const youtubeWrapper = document.getElementById('youtubeWrapper');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'file') {
                fileWrapper.classList.remove('hidden');
                youtubeWrapper.classList.add('hidden');
            } else {
                fileWrapper.classList.add('hidden');
                youtubeWrapper.classList.remove('hidden');
            }
        });
    });
</script>