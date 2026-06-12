<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Monitoring Tugas
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
        
        <div class="mx-auto max-w-7xl">

            {{-- INFO MATERI --}}
            <div class="flex items-center justify-between gap-4 p-4 mb-6 border shadow-sm bg-surface border-custom rounded-2xl">

                {{-- LEFT --}}
                <div>

                    <h3 class="text-lg font-bold text-[var(--text-main)]">

                        {{ $material->title }}

                    </h3>

                </div>

                {{-- RIGHT --}}
                <div class="text-lg font-semibold text-[var(--primary)] whitespace-nowrap">

                    {{ $material->subject->classroom->name }}

                </div>

            </div>

            @php
                $total = $students->count();
                $submitted = $submissions->count();
            @endphp

            {{-- STATS --}}
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">

                {{-- TOTAL --}}
                <div class="p-5 border shadow-sm bg-surface border-custom rounded-2xl">

                    <div class="flex items-center gap-3">

                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-[var(--primary-light)] text-[var(--primary)]">

                            <iconify-icon
                                icon="solar:users-group-rounded-bold-duotone"
                                width="24">
                            </iconify-icon>

                        </div>

                        <div>

                            <div class="text-sm text-[var(--text-tertiary)]">
                                Total Siswa
                            </div>

                            <div class="text-2xl font-bold text-[var(--text-main)]">
                                {{ $total }}
                            </div>

                        </div>

                    </div>

                </div>

                {{-- SUBMIT --}}
                <div class="p-5 border shadow-sm bg-surface border-custom rounded-2xl">

                    <div class="flex items-center gap-3">

                        <div class="flex items-center justify-center w-12 h-12 text-green-500 rounded-xl bg-green-500/15">

                            <iconify-icon
                                icon="solar:check-circle-bold-duotone"
                                width="24">
                            </iconify-icon>

                        </div>

                        <div>

                            <div class="text-sm text-[var(--text-tertiary)]">
                                Sudah Submit
                            </div>

                            <div class="text-2xl font-bold text-green-500">
                                {{ $submitted }}
                            </div>

                        </div>

                    </div>

                </div>

                {{-- BELUM --}}
                <div class="p-5 border shadow-sm bg-surface border-custom rounded-2xl">

                    <div class="flex items-center gap-3">

                        <div class="flex items-center justify-center w-12 h-12 text-red-500 rounded-xl bg-red-500/15">

                            <iconify-icon
                                icon="solar:close-circle-bold-duotone"
                                width="24">
                            </iconify-icon>

                        </div>

                        <div>

                            <div class="text-sm text-[var(--text-tertiary)]">
                                Belum Submit
                            </div>

                            <div class="text-2xl font-bold text-red-500">
                                {{ $total - $submitted }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            
            {{-- FILTER --}}
            <form method="GET" class="mb-4">

                <div class="flex flex-col gap-3 md:flex-row md:flex-wrap md:items-center">

                    {{-- ROW 1 MOBILE --}}
                    <div class="flex gap-2 md:contents">

                        {{-- SEARCH --}}
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari siswa..."
                            class="input-solid flex-1 md:flex-none md:w-fit md:max-w-40
                            bg-[var(--surface)]
                            rounded-xl
                            py-2.5 px-3
                            border-2 border-[var(--border)]
                            shadow-md
                            focus:border-[var(--primary)]">

                        {{-- STATUS --}}
                        <select name="status"
                            class="input-solid flex-1 md:flex-none md:max-w-40 md:min-w-[150px]
                            bg-[var(--surface)]
                            rounded-xl
                            py-2.5
                            border-2 border-[var(--border)]
                            shadow-md
                            focus:border-[var(--primary)]">

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

                    </div>

                    {{-- ROW 2 MOBILE --}}
                    <div class="flex gap-2 md:contents">

                        {{-- FILTER --}}
                        <button
                            class="flex-1 md:flex-none
                            flex items-center justify-center gap-2
                            rounded-xl
                            py-2.5 px-4
                            border-2 border-[var(--primary)]
                            bg-[var(--primary)]
                            text-white
                            shadow-md
                            hover:opacity-90
                            transition-all duration-200">

                            <iconify-icon
                                icon="solar:filter-bold-duotone"
                                width="20">
                            </iconify-icon>

                            <span class="hidden sm:inline">
                                Filter
                            </span>

                        </button>

                        {{-- CLEAR --}}
                        <a href="{{ route('materials.submissions', $material->id) }}"
                            class="flex-1 md:flex-none
                            flex items-center justify-center
                            rounded-xl
                            py-2.5 px-3
                            border-2 border-gray-500
                            bg-gray-500
                            text-white
                            shadow-md
                            hover:bg-gray-600
                            hover:border-gray-600
                            transition-all duration-200">

                            <iconify-icon
                                icon="solar:close-circle-bold-duotone"
                                width="22">
                            </iconify-icon>

                        </a>

                    </div>

                </div>

            </form>

            {{-- TABLE --}}
            <div class="overflow-x-auto card-panel">

                @php
                    $statusMap = [
                        'terkirim' => [
                            'label' => 'Menunggu',
                            'class' => 'badge-warning',
                            'icon' => 'solar:clock-circle-bold-duotone',
                        ],

                        'perbaiki' => [
                            'label' => 'Perbaiki',
                            'class' => 'badge-danger',
                            'icon' => 'solar:danger-triangle-bold-duotone',
                        ],

                        'selesai' => [
                            'label' => 'Selesai',
                            'class' => 'badge-success',
                            'icon' => 'solar:check-circle-bold-duotone',
                        ],
                    ];
                @endphp

                <table class="w-full text-sm table-custom">

                    <thead>

                        <tr>

                            <th class="w-[25%]">
                                Nama Siswa
                            </th>

                            <th class="w-[15%]">
                                Status
                            </th>

                            <th class="w-[15%] !text-center">
                                File
                            </th>

                            <th class="w-[20%] !text-center">
                                Aksi
                            </th>

                            <th class="w-[25%]">
                                Catatan
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($students as $student)

                            @php
                                $submission = $submissions[$student->id] ?? null;
                            @endphp

                            <tr>

                                {{-- NAMA --}}
                                <td class="font-semibold text-[var(--text-main)]">

                                    <div class="flex items-center gap-3">

                                        <div class="flex items-center justify-center w-10 h-10 font-bold rounded-full bg-[var(--primary-light)] text-[var(--primary)]">

                                            {{ strtoupper(substr($student->name, 0, 2)) }}

                                        </div>

                                        <div>

                                            <div class="font-semibold text-[var(--text-main)]">
                                                {{ $student->name }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    @if($submission)

                                        <span class="badge {{ $statusMap[$submission->status]['class'] }}">

                                            <iconify-icon
                                                icon="{{ $statusMap[$submission->status]['icon'] }}">
                                            </iconify-icon>

                                            {{ $statusMap[$submission->status]['label'] }}

                                        </span>

                                    @else

                                        <span class="badge badge-purple">

                                            <iconify-icon
                                                icon="solar:question-circle-bold-duotone">
                                            </iconify-icon>

                                            Belum Submit

                                        </span>

                                    @endif

                                </td>

                                {{-- FILE --}}
                                <td>

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
                                                        class="overflow-hidden transition border shadow-sm cursor-pointer rounded-xl border-custom w-14 h-14 bg-[var(--bg)] hover:scale-105 hover:border-[var(--primary)]">

                                                        <img src="{{ $fileUrl }}"
                                                            class="object-cover w-full h-full">

                                                    </div>

                                                {{-- PDF --}}
                                                @elseif($ext == 'pdf')

                                                    <div
                                                        onclick="openSubmissionPreview('pdf', '{{ $fileUrl }}')"
                                                        class="flex items-center justify-center transition border shadow-sm cursor-pointer rounded-xl border-custom w-14 h-14 bg-[var(--bg)] hover:scale-105 hover:border-red-500">

                                                        <span class="text-xs font-bold text-red-500">
                                                            PDF
                                                        </span>

                                                    </div>

                                                {{-- OTHER --}}
                                                @else

                                                    <a href="{{ $fileUrl }}"
                                                        target="_blank"
                                                        class="flex items-center justify-center transition border shadow-sm rounded-xl border-custom w-14 h-14 bg-[var(--bg)] hover:scale-105">

                                                        <span class="text-xs text-[var(--text-main)]">
                                                            FILE
                                                        </span>

                                                    </a>

                                                @endif

                                            {{-- LINK --}}
                                            @elseif($submission->link)

                                                <div
                                                    onclick="openSubmissionPreview('link', '{{ $submission->link }}')"
                                                    class="flex items-center justify-center transition border shadow-sm cursor-pointer rounded-xl border-custom w-14 h-14 bg-[var(--primary-light)] hover:scale-105">

                                                    <iconify-icon
                                                        icon="solar:link-bold-duotone"
                                                        width="22"
                                                        class="text-[var(--primary)]">
                                                    </iconify-icon>

                                                </div>

                                            @endif

                                        </div>

                                    @else

                                        <div class="text-center text-small">
                                            -
                                        </div>

                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td>

                                    @if($submission)

                                        <div x-data="{ open: false }">

                                            <div class="flex justify-center gap-2">

                                                {{-- SELESAI --}}
                                                @if($submission->status !== 'selesai')

                                                <form method="POST"
                                                    action="{{ route('submissions.complete', $submission->id) }}"
                                                    onsubmit="confirmAction(
                                                        event,
                                                        'Tandai Tugas Selesai?',
                                                        'Tugas siswa akan ditandai selesai',
                                                        'Ya, Selesai',
                                                        'success'
                                                    )">

                                                    @csrf

                                                    <button type="submit"
                                                        class="flex items-center gap-1 px-3 py-1 text-xs shadow-sm rounded-lg transition bg-[var(--success-light)] border border-[var(--success)] text-[var(--success)] hover:bg-[var(--success)] hover:text-white">

                                                        <iconify-icon
                                                            icon="lets-icons:check-fill"
                                                            width="16">
                                                        </iconify-icon>

                                                        Selesai

                                                    </button>

                                                </form>

                                                @endif

                                                {{-- PERBAIKI --}}
                                                @if(!in_array($submission->status, ['perbaiki', 'selesai']))

                                                    <button @click="open = true"
                                                        class="flex items-center gap-1 px-3 py-1 text-xs shadow-sm rounded-lg transition bg-[var(--danger-light)] border border-[var(--danger)] text-[var(--danger)] hover:bg-[var(--danger)] hover:text-white">

                                                        <iconify-icon
                                                            icon="heroicons:x-circle"
                                                            width="16">
                                                        </iconify-icon>

                                                        Perbaiki

                                                    </button>

                                                @elseif($submission->status == 'selesai')

                                                    <div class="w-6 h-6 rounded-full bg-green-500/15"></div>

                                                @endif

                                            </div>

                                            {{-- MODAL --}}
                                            <div x-show="open"
                                                x-cloak
                                                x-transition
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

                                                <div @click.outside="open = false"
                                                    class="w-full max-w-md p-6 border shadow-xl bg-surface rounded-2xl border-custom">

                                                    {{-- HEADER --}}
                                                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-custom">

                                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-500/15">

                                                            <iconify-icon
                                                                icon="heroicons:x-circle"
                                                                class="text-xl text-red-500">
                                                            </iconify-icon>

                                                        </div>

                                                        <div>

                                                            <h3 class="font-bold text-[var(--text-main)]">
                                                                Catatan Perbaikan
                                                            </h3>

                                                            <p class="text-sm text-[var(--text-tertiary)]">
                                                                Berikan alasan revisi tugas
                                                            </p>

                                                        </div>

                                                    </div>

                                                    <form method="POST"
                                                        action="{{ route('submissions.revise', $submission->id) }}"
                                                        onsubmit="confirmAction(
                                                            event,
                                                            'Kirim Revisi Tugas?',
                                                            'Siswa akan diminta memperbaiki tugas',
                                                            'Ya, Kirim',
                                                            'warning'
                                                        )">

                                                        @csrf

                                                        <textarea name="note"
                                                                rows="4"
                                                                class="input-solid w-full bg-[var(--surface)] border-2 border-[var(--border)] shadow-sm rounded-xl focus:border-[var(--danger)]"
                                                                placeholder="Tulis alasan perbaikan..."
                                                                required></textarea>

                                                        {{-- BUTTON --}}
                                                        <div class="flex justify-end gap-3 mt-5">

                                                            <button type="button"
                                                                    @click="open = false"
                                                                    class="btn-outline">

                                                                Batal

                                                            </button>

                                                            <button type="submit"
                                                                class="flex items-center gap-2 shadow-sm text-white px-4 py-2 rounded-xl transition bg-[var(--danger)] hover:bg-red-700">

                                                                <iconify-icon
                                                                    icon="heroicons:paper-airplane"
                                                                    width="18">
                                                                </iconify-icon>

                                                                Kirim

                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    @else

                                        <div class="text-center text-small">
                                            -
                                        </div>

                                    @endif

                                </td>

                                {{-- NOTE --}}
                                <td>

                                    @if($submission && $submission->note)

                                        <div class="max-w-[220px] text-sm break-words text-[var(--text-secondary)]">

                                            {{ $submission->note }}

                                        </div>

                                    @else

                                        <span class="text-small">
                                            -
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- ================= SUBMISSION PREVIEW MODAL ================= --}}
    <div id="submissionPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center hidden bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closeSubmissionPreview()"
            class="absolute z-50 flex items-center justify-center w-12 h-12 text-white transition rounded-full top-4 right-6 bg-black/40 hover:bg-red-500">

            <iconify-icon
                icon="heroicons:x-mark"
                width="28">
            </iconify-icon>

        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="submissionPreviewImage"
                class="hidden object-contain w-full border shadow-2xl max-h-[90vh] rounded-2xl border-custom">

            {{-- PDF --}}
            <iframe id="submissionPreviewPdf"
                class="hidden w-full border shadow-2xl bg-surface rounded-2xl h-[90vh] border-custom">
            </iframe>

            {{-- LINK --}}
            <iframe id="submissionPreviewLink"
                class="hidden w-full border shadow-2xl bg-surface rounded-2xl h-[90vh] border-custom">
            </iframe>

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