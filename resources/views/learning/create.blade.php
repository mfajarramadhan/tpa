<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Tambah Mapel - {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        <div class="p-5 bg-white rounded shadow">

            <form method="POST"
                  action="{{ route('learning.subject.store', $classroom->id) }}">
                @csrf

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Nama Mapel</label>
                    <input type="text" name="name"
                        class="w-full p-2 border rounded"
                        required>
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Deskripsi</label>
                    <textarea name="description"
                        class="w-full p-2 border rounded"></textarea>
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan
                </button>

            </form>

        </div>

    </div>
</x-app-layout>