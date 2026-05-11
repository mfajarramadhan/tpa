<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto space-y-6 max-w-7xl">

            

            {{-- ================= TAMBAH MATERI ================= --}}
            @role('guru|superadmin')
            <div class="mb-4">

                <a href="{{ route('materials.create', $subject->id) }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                    <iconify-icon
                        icon="solar:add-circle-bold-duotone"
                        width="20">
                    </iconify-icon>

                    Tambah Materi

                </a>

            </div>
            @endrole

            {{-- EMPTY --}}
            @if($subject->materials->isEmpty())
                <div class="p-6 text-center text-gray-500 bg-white rounded">
                    Belum ada materi
                </div>
            @endif

            {{-- ================= LIST MATERI ================= --}}
            @foreach($subject->materials as $material)
            <div id="card-{{ $material->id }}" class="relative transition-all duration-300 bg-white border shadow-md rounded-xl">

                @role('guru|superadmin')
                <div x-data="{ open:false }" class="absolute top-4 right-4">

                    <button @click="open = !open"
                        class="px-2 py-1 rounded hover:bg-gray-100">
                        ⋮
                    </button>

                    <div x-show="open"
                        @click.outside="open = false"
                        class="absolute right-0 w-40 mt-2 bg-white border rounded shadow">

                        {{-- EDIT --}}
                        <a href="{{ route('materials.edit', $material->id) }}"
                        class="block px-3 py-2 text-sm hover:bg-gray-100">
                            ✏️ Edit
                        </a>

                        {{-- DELETE --}}
                        <form method="POST"
                            action="{{ route('materials.destroy', $material->id) }}"
                            onsubmit="return confirm('Yakin hapus materi?')">

                            @csrf
                            @method('DELETE')

                            <button class="w-full px-3 py-2 text-sm text-left text-red-500 hover:bg-red-50">
                                🗑️ Hapus
                            </button>
                        </form>

                    </div>

                </div>
                @endrole

                {{-- HEADER (CLICKABLE) --}}
                {{-- HEADER (CLICKABLE) --}}
                <div onclick="toggleMaterial({{ $material->id }})"
                    class="p-6 cursor-pointer rounded-xl hover:bg-slate-50">

                    <div class="flex items-start gap-4">

                        {{-- ICON --}}
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-xl
                            {{ $material->is_task 
                                ? 'bg-orange-100 text-orange-600' 
                                : 'bg-blue-100 text-blue-600' }}">

                            @if($material->is_task)

                                {{-- TUGAS --}}
                                <iconify-icon icon="heroicons:clipboard-document-list"
                                    width="28">
                                </iconify-icon>

                            @else

                                {{-- MATERI --}}
                                <iconify-icon icon="heroicons:book-open"
                                    width="28">
                                </iconify-icon>

                            @endif

                        </div>

                        {{-- TITLE --}}
                        <div class="flex-1 min-w-0">

                            <h3 class="pr-10 text-lg font-bold break-words">
                                {{ $material->title }}
                            </h3>

                            {{-- LABEL --}}
                            <p class="mt-1 text-xs font-medium
                                {{ $material->is_task 
                                    ? 'text-orange-500' 
                                    : 'text-blue-500' }}">

                                {{ $material->is_task ? 'Tugas' : 'Materi' }}

                            </p>

                        </div>

                    </div>

                </div>

                {{-- CONTENT (HIDDEN BY DEFAULT) --}}
                <div id="content-{{ $material->id }}"
                    class="overflow-hidden transition-all duration-500 max-h-0">

                    <div class="px-6 pb-6 border-t">
                        
                        {{-- DESCRIPTION --}}
                        @if($material->description)
                            <div class="mt-4">
                                <p class="text-sm leading-relaxed text-gray-600 whitespace-pre-line">
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
                                        class="overflow-hidden transition bg-gray-100 border cursor-pointer rounded-xl hover:bg-gray-200">

                                        <img src="{{ $fileUrl }}"
                                            class="object-cover w-full h-48">

                                    </div>

                                {{-- ================= PDF ================= --}}
                                @elseif($extension === 'pdf')

                                    <div onclick="openPreviewModal('pdf', '{{ $fileUrl }}')"
                                        class="flex items-center justify-between gap-4 p-4 transition bg-gray-100 border cursor-pointer rounded-xl hover:bg-gray-200">

                                        <div class="flex items-center min-w-0 gap-4">

                                            {{-- MINI PREVIEW --}}
                                            <iframe
                                                src="{{ $fileUrl }}#toolbar=0"
                                                class="flex-shrink-0 hidden w-32 bg-white border rounded h-18 md:block">
                                            </iframe>

                                            {{-- THUMB --}}
                                            <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 text-sm font-bold text-red-500 bg-white border rounded-lg">
                                                PDF
                                            </div>

                                            {{-- INFO --}}
                                            <div class="min-w-0">
                                                <p class="font-semibold text-blue-700 truncate">
                                                    {{ $material->title }}
                                                </p>

                                                <p class="text-sm text-gray-500">
                                                    PDF
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                {{-- ================= OTHER FILE ================= --}}
                                @else

                                    <a href="{{ $fileUrl }}"
                                        target="_blank"
                                        class="flex items-center gap-3 p-4 transition bg-gray-100 border rounded-xl hover:bg-gray-200">

                                        <div class="flex items-center justify-center text-sm bg-white border rounded w-14 h-14">
                                            FILE
                                        </div>

                                        <div>
                                            <p class="font-semibold">
                                                {{ $material->title }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                Download File
                                            </p>
                                        </div>

                                    </a>

                                @endif

                            {{-- ================= YOUTUBE ================= --}}
                            @elseif($material->youtube_link)

                                <div onclick="openPreviewModal('youtube', '{{ $material->youtube_link }}')"
                                    class="relative overflow-hidden transition border cursor-pointer rounded-xl group">

                                    {{-- THUMBNAIL --}}
                                    <img src="https://img.youtube.com/vi/{{ $material->youtube_link }}/hqdefault.jpg"
                                        class="object-cover w-full h-56 transition group-hover:scale-105">

                                    {{-- OVERLAY --}}
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30">

                                        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/90">
                                            ▶
                                        </div>

                                    </div>

                                </div>

                            {{-- ================= EMPTY ================= --}}
                            @else

                                <p class="text-sm italic text-gray-400">
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
                                                'color' => 'bg-yellow-100 text-yellow-700'
                                            ],
                                            'perbaiki' => [
                                                'label' => 'Perbaiki',
                                                'color' => 'bg-red-100 text-red-700'
                                            ],
                                            'selesai' => [
                                                'label' => 'Selesai',
                                                'color' => 'bg-green-100 text-green-700'
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
                                                onclick="window.open('{{ $fileUrl }}')"
                                                class="flex items-center justify-between p-3 transition bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200">

                                                <div class="flex items-center gap-3">

                                                    {{-- PREVIEW --}}
                                                    @if($mySubmission->file_path)

                                                        @if(in_array($ext, ['jpg','jpeg','png']))
                                                            <img src="{{ $fileUrl }}"
                                                                class="object-cover w-12 h-12 rounded">
                                                        @elseif($ext == 'pdf')
                                                            <div class="flex items-center justify-center w-12 h-12 text-xs font-bold text-red-500 bg-white rounded">
                                                                PDF
                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center w-12 h-12 text-xs bg-white rounded">
                                                                FILE
                                                            </div>
                                                        @endif

                                                    @elseif($mySubmission->link)

                                                        <div class="flex items-center justify-center w-12 h-12 text-xl bg-blue-100 rounded">
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
                                                            <div class="mt-1 text-xs text-gray-500">
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
                                                class="block w-full py-2 mt-3 text-center text-white rounded-lg
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
                                            class="block w-full py-2 text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">

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

                                            <div class="text-sm text-gray-600">
                                                {{ $total }} siswa sudah mengumpulkan
                                            </div>

                                            @if(auth()->user()->hasAnyRole(['guru','superadmin']))
                                            <a href="{{ route('materials.submissions', $material->id) }}" onclick="event.stopPropagation()"
                                            class="px-3 py-1 text-xs text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                                Lihat Tugas
                                            </a>
                                            @endif

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
        class="fixed inset-0 z-50 items-center justify-center hidden bg-black/80 backdrop-blur-sm">

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
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
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

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePreviewModal() {

        const modal = document.getElementById('previewModal');

        document.getElementById('previewYoutube').src = '';

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // klik backdrop
    document.getElementById('previewModal').addEventListener('click', function(e) {

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