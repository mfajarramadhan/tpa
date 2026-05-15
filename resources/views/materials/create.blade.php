<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah Materi</h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BACK BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('learning.subject', $subject->id) }}"
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
                action="{{ route('materials.store') }}"
                enctype="multipart/form-data">
                @csrf

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-gray-100">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:clipboard-list-linear"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Tambah Materi/Tugas
                        </h2>

                        <p class="text-sm text-slate-500">
                            Tambahkan materi/tugas baru
                        </p>

                    </div>

                </div>

                {{-- id mata pelajaran --}}
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                {{-- JUDUL --}}
                <div class="mb-4">

                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                        Judul
                    </label>

                    <input type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="input-solid w-full bg-[var(--surface)] border-2 border-[var(--border)] shadow-sm rounded-xl focus:border-[var(--primary)]">

                </div>

                {{-- DESKRIPSI --}}
                <div class="mb-4">

                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                        Deskripsi
                    </label>

                    <textarea name="description"
                            rows="5"
                            class="input-solid w-full bg-[var(--surface)] border-2 border-[var(--border)] shadow-sm rounded-xl focus:border-[var(--primary)]">{{ old('description') }}</textarea>

                </div>

                {{-- PILIH TIPE --}}
                <div class="mb-4">

                    <label class="block mb-3 text-sm font-semibold text-[var(--text-main)]">
                        Jenis Materi
                    </label>

                    <div class="flex flex-wrap gap-3">

                        {{-- FILE --}}
                        <label class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-[var(--border)] bg-[var(--surface)] shadow-sm text-[var(--text-main)] cursor-pointer hover:border-[var(--primary)] hover:bg-[var(--primary-light)] transition-all duration-200">

                            <input type="radio"
                                name="type"
                                value="file"
                                checked
                                class="accent-[var(--primary)]">

                            Upload File

                        </label>

                        {{-- YOUTUBE --}}
                        <label class="flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-[var(--border)] bg-[var(--surface)] shadow-sm text-[var(--text-main)] cursor-pointer hover:border-[var(--primary)] hover:bg-[var(--primary-light)] transition-all duration-200">

                            <input type="radio"
                                name="type"
                                value="youtube"
                                class="accent-[var(--primary)]">

                            YouTube

                        </label>

                    </div>

                </div>

                {{-- FILE --}}
                <div class="mb-4" id="fileWrapper">

                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                        File
                    </label>

                    <input type="file"
                        name="file"
                        id="fileInput"
                        class="w-full p-2 bg-[var(--surface)] border-2 border-[var(--border)] shadow-sm rounded-xl text-[var(--text-main)]">

                    <p class="mt-1 text-xs text-[var(--text-tertiary)]">
                        Hanya PDF/JPG/PNG/JPEG
                    </p>

                </div>

                {{-- YOUTUBE --}}
                <div class="hidden mb-4" id="youtubeWrapper">

                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                        Link YouTube
                    </label>

                    <input type="text"
                        name="youtube_link"
                        id="youtubeInput"
                        placeholder="Contoh: rzbjF2DRv1c"
                        class="input-solid w-full bg-[var(--surface)] border-2 border-[var(--border)] shadow-sm rounded-xl focus:border-[var(--primary)]">

                    <div class="p-4 mt-3 rounded-xl border border-custom bg-[var(--bg)] text-sm text-[var(--text-secondary)]">

                        <p class="font-semibold text-[var(--text-main)] mb-2">
                            Masukkan kode unik link YouTube, bukan link utuh!
                        </p>

                        <p class="mb-1 break-all">
                            Contoh 1:
                            https://youtu.be/<b>BJu1Qcul7ig</b>?si=tmDo0cJ8kL3AzWH0
                            → <b>(BJu1Qcul7ig)</b>
                        </p>

                        <p class="break-all">
                            Contoh 2:
                            https://youtube.com/shorts/<b>ujryWPV3_iI</b>?si=Ro8SpBbkyX4ap47W
                            → <b>(ujryWPV3_iI)</b>
                        </p>

                    </div>

                </div>

                {{-- TASK --}}
                <div class="mb-6">

                    <label class="flex items-center gap-3 px-4 py-3 rounded-xl border border-custom bg-[var(--bg)] text-sm text-[var(--text-main)] shadow-sm">

                        <input type="checkbox"
                            name="is_task"
                            value="1"
                            class="rounded accent-[var(--primary)]">

                        Jadikan sebagai tugas

                    </label>

                </div>

                {{-- BUTTON --}}
                <button class="shadow-sm btn-primary">

                    <iconify-icon
                        icon="solar:diskette-bold-duotone"
                        width="20">
                    </iconify-icon>

                    Simpan

                </button>

            </form>

        </div>
    </div>
</x-app-layout>

<script>
    const radios = document.querySelectorAll('input[name="type"]');
    const fileWrapper = document.getElementById('fileWrapper');
    const youtubeWrapper = document.getElementById('youtubeWrapper');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'file') {
                fileWrapper.classList.remove('hidden');
                youtubeWrapper.classList.add('hidden');
            } else {
                fileWrapper.classList.add('hidden');
                youtubeWrapper.classList.remove('hidden');
            }
        });
    });
</script>