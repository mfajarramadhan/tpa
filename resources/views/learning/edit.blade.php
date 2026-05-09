<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Edit Mapel
        </h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        <div class="p-5 bg-white rounded shadow">

            <form method="POST"
                  action="{{ route('learning.subject.update', $subject->id) }}">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Nama Mapel</label>
                    <input type="text" name="name"
                        value="{{ $subject->name }}"
                        class="w-full p-2 border rounded"
                        required>
                </div>

                {{-- HARI --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Hari
                    </label>

                    <select name="day"
                            class="w-full p-2 border rounded-lg">

                        <option value="1" {{ $subject->day == 1 ? 'selected' : '' }}>
                            Senin
                        </option>

                        <option value="2" {{ $subject->day == 2 ? 'selected' : '' }}>
                            Selasa
                        </option>

                        <option value="3" {{ $subject->day == 3 ? 'selected' : '' }}>
                            Rabu
                        </option>

                        <option value="4" {{ $subject->day == 4 ? 'selected' : '' }}>
                            Kamis
                        </option>

                        <option value="5" {{ $subject->day == 5 ? 'selected' : '' }}>
                            Jumat
                        </option>

                    </select>

                    @error('day')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Update
                </button>

            </form>

        </div>

    </div>
</x-app-layout>