<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            {{ $subject->name }}
        </h2>
    </x-slot>

    {{-- Alert --}}
    <div class="relative">

        {{-- FLOATING ALERT WRAPPER --}}
        <div class="absolute top-0 left-0 z-50 w-full pointer-events-none">

            {{-- SUCCESS --}}
            @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)"
                @click.outside="show = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="pointer-events-auto flex items-center p-3 text-white rounded-xl shadow-md 
                    bg-gradient-to-t from-[var(--primary-dark)] to-[var(--primary)] 
                    bg-opacity-80 backdrop-blur-sm">

                <div class="text-sm font-semibold ms-2">
                    {{ session('success') }}
                </div>

                <button @click="show = false"
                    class="flex items-center justify-center w-8 h-8 font-bold text-black transition rounded-md ms-auto bg-white/80 hover:bg-white">
                    ✕
                </button>
            </div>
            @endif


            {{-- ERROR --}}
            @if(session('error'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)"
                @click.outside="show = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="pointer-events-auto flex items-center p-3 text-white rounded-xl shadow-md 
                    bg-gradient-to-t from-[var(--danger)] to-red-400 
                    bg-opacity-80 backdrop-blur-sm">

                <div class="text-sm font-semibold ms-2">
                    {{ session('error') }}
                </div>

                <button @click="show = false"
                    class="flex items-center justify-center w-8 h-8 font-bold text-black transition rounded-md ms-auto bg-white/80 hover:bg-white">
                    ✕
                </button>
            </div>
            @endif

        </div>

    </div>

    <div class="py-6 md:py-0">
        <div class="flex items-center justify-between mb-6">

            {{-- BACK BUTTON --}}
            <a href="{{ route('learning.classroom', $subject->classroom_id) }}"
            class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

            {{-- TAMBAH MATERI --}}
            @role('guru')
                <a href="{{ route('materials.create', $subject->id) }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                    <iconify-icon
                        icon="solar:add-circle-bold-duotone"
                        width="20">
                    </iconify-icon>

                    Tambah Materi
                </a>
            @endrole
        </div>
        
        <div class="mx-auto space-y-6 max-w-7xl">

            {{-- EMPTY --}}
            @if($subject->materials->isEmpty())
                <div class="p-6 text-center text-[var(--text-tertiary)] bg-surface">
                    Belum ada materi
                </div>
            @endif

            {{-- ================= LIST MATERI ================= --}}
            @foreach($subject->materials as $material)
            <div id="card-{{ $material->id }}" class="relative transition-all duration-300 border shadow-md bg-surface border-custom rounded-2xl">

                @role('guru')
                <div x-data="{ open:false }" class="absolute top-4 right-4">

                    <button @click="open = !open"
                        class="flex items-center justify-center w-9 h-9 transition rounded-xl bg-[var(--bg)] hover:bg-[var(--primary-light)] text-[var(--text-secondary)] border border-custom shadow-sm">
                        ⋮
                    </button>

                    <div x-show="open"
                        @click.outside="open = false"
                        class="absolute right-0 z-50 w-40 mt-2 overflow-hidden border shadow-lg bg-surface border-custom rounded-xl">

                        {{-- EDIT --}}
                        <a href="{{ route('materials.edit', $material->id) }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm transition text-[var(--text-main)] hover:bg-[var(--primary-light)] hover:text-[var(--primary)]">

                            <iconify-icon icon="solar:pen-bold-duotone" width="18"></iconify-icon>

                            Edit

                        </a>

                        {{-- DELETE --}}
                        <form method="POST"
                            action="{{ route('materials.destroy', $material->id) }}"
                            onsubmit="confirmAction(
                                event,
                                'Hapus Materi?',
                                'Materi atau tugas akan dihapus permanen',
                                'Ya, Hapus',
                                'error'
                            )">

                            @csrf
                            @method('DELETE')

                            <button class="flex items-center w-full gap-2 px-4 py-3 text-sm text-left text-red-500 transition hover:bg-red-500/10">

                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone" width="18"></iconify-icon>

                                Hapus

                            </button>

                        </form>

                    </div>

                </div>
                @endrole

                {{-- HEADER --}}
                <div onclick="toggleMaterial({{ $material->id }})"
                    class="p-6 transition rounded-2xl cursor-pointer hover:bg-[var(--primary-light)]">

                    <div class="flex items-start gap-4">

                        {{-- ICON --}}
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-xl
                            {{ $material->is_task
                                ? 'bg-orange-500/15 text-orange-500'
                                : 'bg-[var(--primary-light)] text-[var(--primary)]' }}">

                            @if($material->is_task)

                                <iconify-icon icon="heroicons:clipboard-document-list" width="28"></iconify-icon>

                            @else

                                <iconify-icon icon="heroicons:book-open" width="28"></iconify-icon>

                            @endif

                        </div>

                        {{-- TITLE --}}
                        <div class="flex-1 min-w-0">

                            <h3 class="pr-10 text-lg font-bold break-words text-[var(--text-main)]">
                                {{ $material->title }}
                            </h3>

                            {{-- LABEL --}}
                            <p class="mt-1 text-xs font-medium
                                {{ $material->is_task
                                    ? 'text-orange-500'
                                    : 'text-[var(--primary)]' }}">

                                {{ $material->is_task ? 'Tugas' : 'Materi' }}

                            </p>

                        </div>

                    </div>

                </div>

                {{-- CONTENT --}}
                <div id="content-{{ $material->id }}"
                    class="overflow-hidden transition-all duration-500 max-h-0">

                    <div class="px-6 pb-6 border-t border-custom">

                        {{-- DESCRIPTION --}}
                        @if($material->description)

                            <div class="mt-4">

                                <p class="text-sm leading-relaxed whitespace-pre-line text-[var(--text-secondary)]">
                                    {{ $material->description }}
                                </p>

                            </div>

                        @endif

                        {{-- ================= PREVIEW ================= --}}
                        <div class="mt-4">

                            @if($material->file_path)

                            @php
                                $fileUrl = asset('storage/' . $material->file_path);
                                $extension = strtolower(pathinfo($material->file_path, PATHINFO_EXTENSION));
                                $fileName = basename($material->file_path);
                            @endphp

                                {{-- ================= IMAGE ================= --}}
                                @if(in_array($extension, ['jpg','jpeg','png']))

                                    <div onclick="openPreviewModal('image', '{{ $fileUrl }}')"
                                        class="overflow-hidden transition border border-custom cursor-pointer rounded-xl bg-[var(--bg)] hover:bg-[var(--primary-light)]">

                                        <img src="{{ $fileUrl }}"
                                            class="object-cover w-full h-48">

                                    </div>

                                {{-- ================= PDF ================= --}}
                                @elseif($extension === 'pdf')

                                    <div onclick="openPreviewModal('pdf', '{{ $fileUrl }}')"
                                        class="flex items-center justify-between gap-4 p-4 transition border border-custom cursor-pointer rounded-xl bg-[var(--bg)] hover:bg-[var(--primary-light)]">

                                        <div class="flex items-center min-w-0 gap-4">

                                            {{-- THUMB --}}
                                            <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 text-sm font-bold text-red-500 border rounded-lg bg-surface border-custom">
                                                PDF
                                            </div>

                                            {{-- INFO --}}
                                            <div class="min-w-0">

                                                <p class="font-semibold truncate text-[var(--primary)]">
                                                    {{ $material->title }}
                                                </p>

                                                <p class="text-sm text-[var(--text-tertiary)]">
                                                    PDF
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                {{-- ================= OTHER FILE ================= --}}
                                @else

                                    <a href="{{ $fileUrl }}"
                                        target="_blank"
                                        class="flex items-center gap-3 p-4 transition border border-custom rounded-xl bg-[var(--bg)] hover:bg-[var(--primary-light)]">

                                        <div class="flex items-center justify-center text-sm border rounded w-14 h-14 bg-surface border-custom text-[var(--text-main)]">
                                            FILE
                                        </div>

                                        <div>

                                            <p class="font-semibold text-[var(--text-main)]">
                                                {{ $material->title }}
                                            </p>

                                            <p class="text-sm text-[var(--text-tertiary)]">
                                                Download File
                                            </p>

                                        </div>

                                    </a>

                                @endif

                            {{-- ================= YOUTUBE ================= --}}
                            @elseif($material->youtube_link)

                                <div onclick="openPreviewModal('youtube', '{{ $material->youtube_link }}')"
                                    class="relative overflow-hidden transition border cursor-pointer border-custom rounded-xl group">

                                    {{-- THUMBNAIL --}}
                                    <img src="https://img.youtube.com/vi/{{ $material->youtube_link }}/hqdefault.jpg"
                                        class="object-cover w-full h-56 transition group-hover:scale-105">

                                    {{-- OVERLAY --}}
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40">

                                        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/90">
                                            ▶
                                        </div>

                                    </div>

                                </div>

                            {{-- ================= EMPTY ================= --}}
                            @else

                                <p class="text-sm italic text-[var(--text-tertiary)]">
                                    Materi belum memiliki file atau video
                                </p>

                            @endif

                        </div>

                        {{-- ================= TUGAS ================= --}}
                        @if($material->is_task)

                            <div class="mt-4 space-y-3">

                                {{-- ================= SUBMISSION SISWA ================= --}}
                                @if(auth()->user()->hasRole('siswa'))

                                    @php
                                        $mySubmission = $material->submissions
                                            ->where('student_id', auth()->user()->student->id)
                                            ->first();

                                        $statusMap = [
                                            'terkirim' => [
                                                'label' => 'Terkirim',
                                                'color' => 'bg-yellow-500/15 text-yellow-500'
                                            ],
                                            'perbaiki' => [
                                                'label' => 'Perbaiki',
                                                'color' => 'bg-red-500/15 text-red-500'
                                            ],
                                            'selesai' => [
                                                'label' => 'Selesai',
                                                'color' => 'bg-green-500/15 text-green-500'
                                            ]
                                        ];
                                    @endphp

                                    {{-- SUDAH SUBMIT --}}
                                    @if($mySubmission)

                                        @php
                                            $fileUrl = $mySubmission->file_path
                                                ? asset('storage/' . $mySubmission->file_path)
                                                : $mySubmission->link;

                                            $ext = $mySubmission->file_path
                                                ? strtolower(pathinfo($mySubmission->file_path, PATHINFO_EXTENSION))
                                                : null;
                                        @endphp

                                        <div class="mt-4">

                                            {{-- CLICKABLE CARD --}}
                                            <div

                                                @if($mySubmission->file_path)

                                                    @if(in_array($ext, ['jpg','jpeg','png']))

                                                        onclick="openPreviewModal('image', '{{ $fileUrl }}')"

                                                    @elseif($ext == 'pdf')

                                                        onclick="openPreviewModal('pdf', '{{ $fileUrl }}')"

                                                    @endif

                                                @elseif($mySubmission->link)

                                                    onclick="window.open('{{ $mySubmission->link }}', '_blank')"

                                                @endif

                                                class="flex items-center justify-between p-3 transition cursor-pointer rounded-xl border border-custom bg-[var(--bg)] hover:bg-[var(--primary-light)]">

                                                <div class="flex items-center gap-3">

                                                    {{-- PREVIEW --}}
                                                    @if($mySubmission->file_path)

                                                        @if(in_array($ext, ['jpg','jpeg','png']))

                                                            <img src="{{ $fileUrl }}"
                                                                class="object-cover w-12 h-12 rounded">

                                                        @elseif($ext == 'pdf')

                                                            <div class="flex items-center justify-center w-12 h-12 text-xs font-bold text-red-500 border rounded border-custom bg-surface">
                                                                PDF
                                                            </div>

                                                        @else

                                                            <div class="flex items-center justify-center w-12 h-12 text-xs rounded border border-custom bg-surface text-[var(--text-main)]">
                                                                FILE
                                                            </div>

                                                        @endif

                                                    @elseif($mySubmission->link)

                                                        <div class="flex items-center justify-center w-12 h-12 text-xl rounded bg-[var(--primary-light)] text-[var(--primary)]">
                                                            🔗
                                                        </div>

                                                    @endif

                                                    {{-- INFO --}}
                                                    <div>

                                                        {{-- STATUS --}}
                                                        <span class="px-2 py-1 text-xs rounded {{ $statusMap[$mySubmission->status]['color'] }}">
                                                            {{ $statusMap[$mySubmission->status]['label'] }}
                                                        </span>

                                                        {{-- NOTE --}}
                                                        @if($mySubmission->note)

                                                            <div class="mt-1 text-xs text-[var(--text-tertiary)]">

                                                                Catatan:
                                                                {{ $mySubmission->note }}

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>

                                            </div>

                                            {{-- BUTTON ACTION --}}
                                            @if($mySubmission->status != 'selesai')

                                                <a href="{{ route('submissions.create', $material->id) }}"
                                                class="block w-full py-2 mt-3 text-center text-white rounded-xl shadow-sm transition

                                                {{ $mySubmission->status == 'perbaiki'
                                                    ? 'bg-red-500 hover:bg-red-600'
                                                    : 'bg-yellow-500 hover:bg-yellow-600' }}">

                                                    {{ $mySubmission->status == 'perbaiki'
                                                        ? 'Upload Revisi'
                                                        : 'Upload Ulang' }}

                                                </a>

                                            @endif

                                        </div>

                                    {{-- BELUM SUBMIT --}}
                                    @else

                                        <div class="mt-4">

                                            <a href="{{ route('submissions.create', $material->id) }}"
                                            class="block w-full py-2 text-center text-white rounded-xl shadow-sm transition bg-[var(--primary)] hover:opacity-90">

                                                Upload Tugas

                                            </a>

                                        </div>

                                    @endif

                                    @endif

                                    {{-- ================= GURU / ADMIN VIEW ================= --}}
                                    @if(auth()->user()->hasAnyRole(['guru','superadmin']))

                                        @php
                                            $total = $material->submissions->count();
                                        @endphp

                                        <div class="flex items-center justify-between mt-4">

                                            <div class="text-sm text-[var(--text-secondary)]">
                                                {{ $total }} siswa sudah mengumpulkan
                                            </div>

                                            <a href="{{ route('materials.submissions', $material->id) }}"
                                            onclick="event.stopPropagation()"
                                            class="flex items-center gap-2 shadow-sm btn-primary">

                                                <iconify-icon
                                                    icon="solar:clipboard-check-bold-duotone"
                                                    width="20">
                                                </iconify-icon>

                                                Lihat Tugas

                                            </a>

                                        </div>

                                    @endif

                            </div>

                        @endif
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    
    {{-- ================= UNIVERSAL PREVIEW MODAL ================= --}}
    <div id="previewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closePreviewModal()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        {{-- CONTENT --}}
        <div class="relative w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="previewImage"
                class="hidden max-w-full mx-auto rounded-lg max-h-[90vh] shadow-2xl">

            {{-- PDF --}}
            <iframe id="previewPdf"
                class="hidden w-full bg-surface rounded-lg h-[90vh] shadow-2xl">
            </iframe>

            {{-- YOUTUBE --}}
            <div id="previewYoutubeWrapper"
                class="hidden aspect-video">

                <iframe id="previewYoutube"
                    class="w-full h-full rounded-lg shadow-2xl"
                    allowfullscreen>
                </iframe>

            </div>

        </div>

    </div>
</x-app-layout>

<script>

    function openPreviewModal(type, src) {

        const modal = document.getElementById('previewModal');

        const image = document.getElementById('previewImage');
        const pdf = document.getElementById('previewPdf');
        const yt = document.getElementById('previewYoutube');
        const ytWrap = document.getElementById('previewYoutubeWrapper');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');
        ytWrap.classList.add('hidden');

        // IMAGE
        if (type === 'image') {

            image.src = src;
            image.classList.remove('hidden');

        }

        // PDF
        if (type === 'pdf') {

            pdf.src = src;
            pdf.classList.remove('hidden');

        }

        // YOUTUBE
        if (type === 'youtube') {

            yt.src = `https://www.youtube.com/embed/${src}?autoplay=1`;

            ytWrap.classList.remove('hidden');

        }

        modal.style.display = 'flex';

    }

    function closePreviewModal() {

        const modal = document.getElementById('previewModal');

        // reset youtube
        document.getElementById('previewYoutube').src = '';

        // reset pdf
        document.getElementById('previewPdf').src = '';

        modal.style.display = 'none';

    }

    // klik backdrop
    document.getElementById('previewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'previewModal') {

                closePreviewModal();

            }

        });

    function toggleMaterial(id) {

        const content = document.getElementById('content-' + id);
        const card = document.getElementById('card-' + id);

        const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

        // tutup semua
        document.querySelectorAll('[id^="content-"]').forEach(el => {
            el.style.maxHeight = '0px';
        });

        document.querySelectorAll('[id^="card-"]').forEach(el => {
            el.classList.remove('border-[var(--primary)]', 'ring-2', 'ring-[var(--primary)]');
        });

        if (!isOpen) {

            content.style.maxHeight = content.scrollHeight + "px";

            card.classList.add(
                'border-[var(--primary)]',
                'ring-2',
                'ring-[var(--primary)]'
            );
        }
    }

</script>