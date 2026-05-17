<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Edit Tahun Akademik
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BACK --}}
        <div class="mb-6">

            <a href="{{ route('academic-years.index') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>

        {{-- FORM --}}
        <div class="mx-auto max-w-7xl">

            <div class="p-6 border shadow-sm bg-surface border-custom rounded-2xl">

                <form method="POST"
                    action="{{ route('academic-years.update', $academicYear->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- HEADER --}}
                    <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                            <iconify-icon
                                icon="solar:calendar-bold-duotone"
                                class="text-xl text-[var(--primary)]">
                            </iconify-icon>

                        </div>

                        <div>

                            <h2 class="text-xl font-bold text-[var(--text-main)]">
                                Edit Tahun Akademik
                            </h2>

                            <p class="text-sm text-[var(--text-tertiary)]">
                                Perbarui informasi tahun akademik
                            </p>

                        </div>

                    </div>

                    {{-- NAME --}}
                    <div>

                        <label class="block mb-1 text-sm font-semibold text-[var(--text-main)]">
                            Tahun Akademik
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name', $academicYear->name) }}"
                            placeholder="Contoh: 2026/2027"
                            class="input-solid"
                            required>

                        @error('name')

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

    </div>

</x-app-layout>