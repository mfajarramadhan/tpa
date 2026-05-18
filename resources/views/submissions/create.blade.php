<x-app-layout>

    <x-slot name="header">

        <h2 class="text-xl font-semibold">
            Upload Tugas
        </h2>

    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BACK BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('learning.subject', $material->subject_id) }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>

        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST"
                action="{{ route('submissions.store', $material->id) }}"
                enctype="multipart/form-data">

                @csrf

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:upload-bold-duotone"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-[var(--text-main)]">
                            Upload Tugas
                        </h2>

                        <p class="text-sm text-[var(--text-tertiary)]">
                            Upload file atau link tugas
                        </p>

                    </div>

                </div>

                {{-- PILIH TIPE --}}
                <div class="mb-4">

                    <label class="block mb-3 text-sm font-semibold text-[var(--text-main)]">
                        Jenis Pengumpulan
                    </label>

                    <div class="flex flex-wrap gap-3">

                        {{-- FILE --}}
                        <label class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-[var(--border)] bg-[var(--surface)] shadow-sm text-[var(--text-main)] cursor-pointer hover:border-[var(--primary)] hover:bg-[var(--primary-light)] transition-all duration-200">

                            <input type="radio"
                                name="type"
                                value="file"
                                {{ old('type', 'file') == 'file' ? 'checked' : '' }}
                                class="accent-[var(--primary)]">

                            Upload File

                        </label>

                        {{-- LINK --}}
                        <label class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-[var(--border)] bg-[var(--surface)] shadow-sm text-[var(--text-main)] cursor-pointer hover:border-[var(--primary)] hover:bg-[var(--primary-light)] transition-all duration-200">

                            <input type="radio"
                                name="type"
                                value="link"
                                {{ old('type') == 'link' ? 'checked' : '' }}
                                class="accent-[var(--primary)]">

                            Link

                        </label>

                    </div>

                    @error('type')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                {{-- FILE --}}
                <div class="mb-4 {{ old('type') == 'link' ? 'hidden' : '' }}"
                    id="fileWrapper">

                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                        Upload File
                    </label>

                    <input type="file"
                        name="file"
                        id="fileInput"
                        class="w-full p-2 bg-[var(--surface)] border-2 shadow-sm rounded-xl text-[var(--text-main)]
                        {{ $errors->has('file')
                            ? 'border-red-500'
                            : 'border-[var(--border)]' }}">

                    @error('file')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                {{-- LINK --}}
                <div class="mb-4 {{ old('type') == 'link' ? '' : 'hidden' }}"
                    id="linkWrapper">

                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                        Link Tugas
                    </label>

                    <input type="text"
                        name="link"
                        id="linkInput"
                        value="{{ old('link') }}"
                        placeholder="https://..."
                        class="input-solid w-full bg-[var(--surface)] border-2 shadow-sm rounded-xl
                        {{ $errors->has('link')
                            ? 'border-red-500'
                            : 'border-[var(--border)] focus:border-[var(--primary)]' }}">

                    @error('link')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

                {{-- BUTTON --}}
                <button class="shadow-sm btn-primary">

                    <iconify-icon
                        icon="solar:upload-bold-duotone"
                        width="20">
                    </iconify-icon>

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