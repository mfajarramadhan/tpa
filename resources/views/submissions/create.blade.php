<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Upload Tugas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl p-6 mx-auto card-panel">

            <form method="POST"
                  action="{{ route('submissions.store', $material->id) }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="material_id" value="{{ $material->id }}">

                {{-- PILIH TIPE --}}
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-semibold">Jenis Pengumpulan</label>

                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="file" checked>
                            Upload File
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="link">
                            Link
                        </label>
                    </div>

                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FILE --}}
                <div class="mb-4" id="fileWrapper">
                    <label class="block mb-1 text-sm font-semibold">Upload File</label>
                    <input type="file" name="file" id="fileInput"
                        class="w-full p-2 border rounded-lg">

                    @error('file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- LINK --}}
                <div class="hidden mb-4" id="linkWrapper">
                    <label class="block mb-1 text-sm font-semibold">Link Tugas</label>
                    <input type="text" name="link" id="linkInput"
                        placeholder="https://..."
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    @error('link')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Upload
                </button>

            </form>

        </div>
    </div>
</x-app-layout>

<script>
    const radios = document.querySelectorAll('input[name="type"]');
    const fileWrapper = document.getElementById('fileWrapper');
    const linkWrapper = document.getElementById('linkWrapper');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'file') {
                fileWrapper.classList.remove('hidden');
                linkWrapper.classList.add('hidden');
            } else {
                fileWrapper.classList.add('hidden');
                linkWrapper.classList.remove('hidden');
            }
        });
    });
</script>