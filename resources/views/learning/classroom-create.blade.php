<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Tambah Kelas
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
                  action="{{ route('learning.classroom.store') }}">

                @csrf

                {{-- NAMA --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Nama Kelas
                    </label>

                    <input type="text"
                           name="name"
                           maxlength="10"
                           value="{{ old('name') }}"
                           placeholder="Contoh: DTA 1"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
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

                        <div class="flex items-center gap-2">

                            Simpan

                        </div>

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>