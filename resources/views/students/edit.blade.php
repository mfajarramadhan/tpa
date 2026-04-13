<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Anak</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">

            <form method="POST" action="{{ route('students.update', $student->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ $student->name }}" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label>Alamat</label>
                    <textarea name="address" class="w-full p-2 border rounded">{{ $student->address }}</textarea>
                </div>

                <button class="px-4 py-2 text-white bg-yellow-500 rounded">
                    Update
                </button>

            </form>

        </div>
    </div>
</x-app-layout>