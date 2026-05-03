<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah Materi</h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        <form method="POST"
              action="{{ route('materials.store') }}"
              enctype="multipart/form-data"
              class="p-5 bg-white rounded shadow">

            @csrf

            <input type="hidden" name="subject_id" value="{{ $subject->id }}">

            <input type="text" name="title"
                   placeholder="Judul"
                   class="w-full p-2 mb-3 border rounded" required>

            <textarea name="description"
                      placeholder="Deskripsi"
                      class="w-full p-2 mb-3 border rounded"></textarea>

            <input type="file" name="file"
                   class="w-full p-2 mb-3 border rounded">

            <input type="text" name="youtube_link"
                   placeholder="Link YouTube (opsional)"
                   class="w-full p-2 mb-3 border rounded">

            <div class="mb-3">
                <label class="flex items-center gap-2 text-sm">

                    <input type="checkbox"
                        name="is_task"
                        value="1"
                        class="rounded">

                    Jadikan sebagai tugas

                </label>
             </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Simpan
            </button>

        </form>

    </div>
</x-app-layout>