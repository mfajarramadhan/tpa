<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Buat Tugas</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('assignments.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- KELAS --}}
                <div class="mb-4">
                    <label class="block mb-1">Kelas</label>
                    <select name="classroom_id" class="w-full p-2 border rounded">
                        @foreach($classrooms as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- JUDUL --}}
                <div class="mb-4">
                    <label>Judul</label>
                    <input type="text"
                           name="title"
                           class="w-full p-2 border rounded">
                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-4">
                    <label>Deskripsi</label>
                    <textarea name="description"
                              class="w-full p-2 border rounded"></textarea>
                </div>

                {{-- DEADLINE --}}
                <div class="mb-4">
                    <label>Deadline</label>
                    <input type="date"
                           name="deadline"
                           class="w-full p-2 border rounded">
                </div>

                {{-- FILE --}}
                <div class="mb-4">
                    <label>Upload File (opsional)</label>
                    <input type="file"
                           name="file"
                           class="w-full p-2 border rounded">
                </div>

                {{-- SUBMIT --}}
                <button
                    onclick="this.disabled=true;this.form.submit();"
                    class="px-4 py-2 text-white transition bg-blue-600 rounded hover:bg-blue-700">
                    Simpan Tugas
                </button>

            </form>

        </div>
    </div>
</x-app-layout>