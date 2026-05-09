<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Monitoring Tugas
        </h2>
    </x-slot>

    {{-- ================= SUBMISSION PREVIEW MODAL ================= --}}
    <div id="submissionPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center hidden bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closeSubmissionPreview()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="submissionPreviewImage"
                class="hidden object-contain w-full max-h-[90vh] rounded-lg shadow-2xl">

            {{-- PDF --}}
            <iframe id="submissionPreviewPdf"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

            {{-- LINK --}}
            <iframe id="submissionPreviewLink"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>

    <div class="py-6">
        <div class="max-w-5xl mx-auto">

            <form method="GET" class="flex flex-wrap gap-2 mb-4">

                {{-- SEARCH --}}
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari siswa..."
                    class="px-3 py-2 text-sm border rounded-lg">

                {{-- FILTER STATUS --}}
                <select name="status" class="px-3 py-2 text-sm border rounded-lg">

                    <option value="">Semua Status</option>

                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>
                        Belum Submit
                    </option>

                    <option value="terkirim" {{ request('status') == 'terkirim' ? 'selected' : '' }}>
                        Terkirim
                    </option>

                    <option value="perbaiki" {{ request('status') == 'perbaiki' ? 'selected' : '' }}>
                        Perbaiki
                    </option>

                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>

                {{-- BUTTON --}}
                <button class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    Filter
                </button>

                {{-- RESET --}}
                <a href="{{ route('materials.submissions', $material->id) }}"
                class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                    Reset
                </a>

            </form>

            {{-- INFO MATERI --}}
            <div class="p-4 mb-4 bg-white rounded shadow">
                <h3 class="font-bold">
                    {{ $material->title }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ $material->subject->classroom->name }}
                </p>
            </div>

            @php
                $total = $students->count();
                $submitted = $submissions->count();
            @endphp

                <div class="p-4 mb-4 bg-white rounded shadow">
                    <div class="flex justify-between text-sm">

                        <div>
                            Total Siswa: <strong>{{ $total }}</strong>
                        </div>

                        <div class="text-green-600">
                            Sudah Submit: {{ $submitted }}
                        </div>

                        <div class="text-red-500">
                            Belum Submit: {{ $total - $submitted }}
                        </div>

                    </div>
                </div>

            {{-- TABLE --}}
            <div class="overflow-hidden bg-white rounded shadow">
                
                @php
                $statusMap = [
                    'terkirim' => ['label' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-700'],
                    'perbaiki' => ['label' => 'Perbaiki', 'color' => 'bg-red-100 text-red-700'],
                    'selesai'  => ['label' => 'Selesai', 'color' => 'bg-green-100 text-green-700'],
                ];
                @endphp
                
                <table class="w-full text-sm">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Nama Siswa</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">File</th>
                            <th class="p-3 text-center">Aksi</th>
                            <th class="p-3 text-center">Note</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($students as $student)

                            @php
                                $submission = $submissions[$student->id] ?? null;
                            @endphp

                                <tr class="transition border-t hover:bg-gray-50">

                                {{-- NAMA --}}
                                <td class="p-3 font-semibold text-[var(--text-main)]">
                                    {{ $student->name }}
                                </td>

                                {{-- STATUS --}}
                                <td class="p-3 text-center">

                                    @if($submission)

                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($submission->status == 'selesai') bg-green-100 text-green-700
                                            @elseif($submission->status == 'perbaiki') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700
                                            @endif
                                        ">
                                            {{ ucfirst($submission->status) }}
                                        </span>

                                    @else

                                        <span class="px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded">
                                            Belum Kirim
                                        </span>

                                    @endif

                                </td>

                                <td class="p-3 text-center">

                                    @if($submission)

                                        <div class="flex justify-center">

                                            {{-- FILE --}}
                                            @if($submission->file_path)

                                                @php
                                                    $fileUrl = asset('storage/' . $submission->file_path);
                                                    $ext = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION));
                                                @endphp

                                                {{-- IMAGE --}}
                                                @if(in_array($ext, ['jpg','jpeg','png']))

                                                    <div
                                                        onclick="openSubmissionPreview('image', '{{ $fileUrl }}')"
                                                        class="overflow-hidden transition bg-gray-100 rounded cursor-pointer w-14 h-14 hover:scale-105">

                                                        <img src="{{ $fileUrl }}"
                                                            class="object-cover w-full h-full">

                                                    </div>

                                                {{-- PDF --}}
                                                @elseif($ext == 'pdf')

                                                    <div
                                                        onclick="openSubmissionPreview('pdf', '{{ $fileUrl }}')"
                                                        class="flex items-center justify-center transition bg-gray-100 rounded cursor-pointer w-14 h-14 hover:scale-105">

                                                        <span class="text-xs font-bold text-red-500">
                                                            PDF
                                                        </span>

                                                    </div>

                                                {{-- OTHER --}}
                                                @else

                                                    <a href="{{ $fileUrl }}"
                                                        target="_blank"
                                                        class="flex items-center justify-center transition bg-gray-100 rounded w-14 h-14 hover:scale-105">

                                                        <span class="text-xs">
                                                            FILE
                                                        </span>

                                                    </a>

                                                @endif

                                            {{-- LINK --}}
                                            @elseif($submission->link)

                                                <div
                                                    onclick="openSubmissionPreview('link', '{{ $submission->link }}')"
                                                    class="flex items-center justify-center transition bg-blue-100 rounded cursor-pointer w-14 h-14 hover:scale-105">

                                                    🔗

                                                </div>

                                            @endif

                                        </div>

                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td class="p-3 text-center">

                                    @if($submission)

                                        <div x-data="{ open: false }">

                                            <div class="flex justify-center gap-2">

                                                {{-- SELESAI --}}
                                                @if($submission->status !== 'selesai')
                                                <form method="POST"
                                                    action="{{ route('submissions.complete', $submission->id) }}"
                                                    onsubmit="return confirm('Tandai tugas selesai?')">

                                                    @csrf

                                                    <button class="btn-icon group bg-[var(--success-light)] border border-[var(--success)] hover:bg-[var(--success)]"
                                                            title="Selesai">

                                                        <iconify-icon icon="lets-icons:check-fill"
                                                                    width="18"
                                                                    class="text-[var(--success)] group-hover:text-white transition">
                                                        </iconify-icon>

                                                    </button>

                                                </form>
                                                @endif

                                                {{-- PERBAIKI --}}
                                                @if(!in_array($submission->status, ['perbaiki', 'selesai']))

                                                    <button @click="open = true"
                                                        class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]"
                                                        title="Perbaiki">

                                                        <iconify-icon icon="heroicons:x-circle"
                                                                    width="18"
                                                                    class="text-[var(--danger)] group-hover:text-white transition">
                                                        </iconify-icon>

                                                    </button>
                                                
                                                @elseif($submission->status == 'selesai')

                                                    <div
                                                        class="h-3 bg-gray-200 border border-gray-300 rounded-lg w-9">
                                                    </div>

                                                @endif

                                            </div>

                                            {{-- MODAL --}}
                                            <div x-show="open"
                                                x-cloak
                                                x-transition
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

                                                <div @click.outside="open = false"
                                                    class="w-full max-w-md p-5 shadow-md bg-surface rounded-xl">

                                                    <h3 class="mb-3 font-semibold">
                                                        Catatan Perbaikan
                                                    </h3>

                                                    <form method="POST"
                                                        action="{{ route('submissions.revise', $submission->id) }}">

                                                        @csrf

                                                        <textarea name="note"
                                                                rows="3"
                                                                class="input-solid"
                                                                placeholder="Tulis alasan perbaikan..."
                                                                required></textarea>

                                                        <div class="flex justify-end gap-2 mt-4">

                                                            <button type="button"
                                                                    @click="open = false"
                                                                    class="btn-outline">

                                                                Batal

                                                            </button>

                                                            <button class="btn-primary bg-[var(--danger)] hover:bg-red-700">

                                                                Kirim

                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif

                                </td>

                                {{-- NOTE --}}
                                <td class="p-3 text-sm text-center">

                                    @if($submission && $submission->note)

                                        <div class="max-w-[220px] mx-auto text-xs text-gray-600">
                                            {{ $submission->note }}
                                        </div>

                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>
    </div>
</x-app-layout>

<script>

    function openSubmissionPreview(type, src) {

        const modal = document.getElementById('submissionPreviewModal');

        const image = document.getElementById('submissionPreviewImage');
        const pdf = document.getElementById('submissionPreviewPdf');
        const link = document.getElementById('submissionPreviewLink');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');
        link.classList.add('hidden');

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

        // LINK
        if (type === 'link') {
            link.src = src;
            link.classList.remove('hidden');
        }

        modal.style.display = 'flex';
    }

    function closeSubmissionPreview() {

        const modal = document.getElementById('submissionPreviewModal');

        document.getElementById('submissionPreviewPdf').src = '';
        document.getElementById('submissionPreviewLink').src = '';
        document.getElementById('submissionPreviewImage').src = '';

        modal.style.display = 'none';
    }

    // klik backdrop
    document.getElementById('submissionPreviewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'submissionPreviewModal') {
                closeSubmissionPreview();
            }

        });

</script>