<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Edit Mapel
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BUTTON --}}
        <div class="mb-6">

            <a href="javascript:history.back()"
               class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>

        {{-- CARD --}}
        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST"
                  action="{{ route('learning.subject.update', $subject->id) }}">
                @csrf
                @method('PUT')

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:clipboard-check-linear"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-[var(--text-main)]">
                            Edit Mata Pelajaran
                        </h2>

                        <p class="text-sm text-[var(--text-tertiary)]">
                            Perbarui informasi mata pelajaran
                        </p>

                    </div>

                </div>

                {{-- NAMA --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Nama Mapel
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $subject->name }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                           required>

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- HARI --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Hari
                    </label>

                    <select name="day"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

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

                {{-- BUTTON --}}
                <div class="mt-6">

                    <button class="shadow-sm btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>