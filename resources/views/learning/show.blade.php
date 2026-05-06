<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto space-y-6 max-w-7xl">

            

            {{-- ================= TAMBAH MATERI ================= --}}
            @role('guru|superadmin')
            <div class="flex justify-end">
                <a href="{{ route('materials.create', $subject->id) }}"
                   class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    + Tambah Materi
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
                <div onclick="toggleMaterial({{ $material->id }})"
                    class="p-6 cursor-pointer rounded-xl hover:bg-slate-50">
                    
                    {{-- TITLE --}}
                    <h3 class="pr-10 text-lg font-bold break-words">
                        {{ $material->title }}
                    </h3>

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

                                {{-- ================= FILE ================= --}}
                                @php
                                    $fileUrl = asset('storage/' . $material->file_path);
                                    $extension = strtolower(pathinfo($material->file_path, PATHINFO_EXTENSION));
                                @endphp

                                {{-- IMAGE --}}
                                @if(in_array($extension, ['jpg','jpeg','png']))
                                    <img src="{{ $fileUrl }}" 
                                        class="w-full rounded-lg shadow cursor-pointer"
                                        onclick="openLightbox('{{ $fileUrl }}')">

                                {{-- PDF --}}
                                @elseif($extension === 'pdf')
                                    <div class="space-y-2">

                                        <iframe 
                                            src="{{ $fileUrl }}#toolbar=1&navpanes=0&scrollbar=1"
                                            class="w-full h-[600px] rounded-lg border">
                                        </iframe>

                                        <div class="flex gap-2">
                                            <a href="{{ $fileUrl }}" target="_blank"
                                            class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                                                Buka Tab Baru
                                            </a>
                                        </div>

                                    </div>

                                {{-- FALLBACK --}}
                                @else
                                    <a href="{{ $fileUrl }}" target="_blank"
                                    class="inline-block px-3 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Download File
                                    </a>
                                @endif

                            @elseif($material->youtube_link)

                                {{-- ================= YOUTUBE ================= --}}
                                <div class="aspect-video">
                                    <iframe 
                                        src="https://www.youtube.com/embed/{{ $material->youtube_link }}"
                                        class="w-full h-full rounded-lg shadow"
                                        allowfullscreen>
                                    </iframe>
                                </div>

                            @else

                                {{-- ================= EMPTY ================= --}}
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
                                            <a href="{{ route('materials.submissions', $material->id) }}"
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
    <div id="lightbox" 
     class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-80">

    <img id="lightboxImg" class="max-w-4xl max-h-[90vh] rounded-lg shadow-lg">

</div>
</x-app-layout>

<script>
    function openLightbox(src) {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightboxImg');

        img.src = src;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
    }

    document.getElementById('lightbox').addEventListener('click', function () {
        this.classList.add('hidden');
    });
    

    function toggleMaterial(id) {

        const content = document.getElementById('content-' + id);
        const card = document.getElementById('card-' + id);

        const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

        // tutup semua dulu (accordion mode)
        document.querySelectorAll('[id^="content-"]').forEach(el => {
            el.style.maxHeight = '0px';
        });

        document.querySelectorAll('[id^="card-"]').forEach(el => {
            el.classList.remove('border-[var(--primary)]', 'ring-2', 'ring-[var(--primary)]');
        });

        // buka jika sebelumnya tertutup
        if (!isOpen) {
            content.style.maxHeight = content.scrollHeight + "px";

            // highlight aktif
            card.classList.add('border-[var(--primary)]', 'ring-2', 'ring-[var(--primary)]');
        }
    }
</script>