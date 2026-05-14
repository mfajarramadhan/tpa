<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Anak</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        
        {{-- BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('students.index') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>
        
        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-gray-100">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="heroicons:user-solid"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Edit Anak
                        </h2>

                        <p class="text-sm text-slate-500">
                            Perbarui informasi detail anak
                        </p>

                    </div>

                </div>

                {{-- NAMA --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Nama
                    </label>

                    <input type="text"
                        name="name"
                        value="{{ old('name', $student->name) }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Tanggal Lahir
                    </label>

                    <input type="date"
                        name="birth_date"
                        value="{{ old('birth_date', $student->birth_date) }}"
                        max="{{ now()->subYears(8)->format('Y-m-d') }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    @error('birth_date')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- SEKOLAH --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Sekolah Asal
                    </label>

                    <input type="text"
                        name="school_origin"
                        value="{{ old('school_origin', $student->school_origin) }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    @error('school_origin')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- KELAS DI SEKOLAH --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Kelas di Sekolah
                    </label>

                    <select name="school_grade"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                        <option value="1 SD" {{ old('school_grade', $student->school_grade) == '1 SD' ? 'selected' : '' }}>
                            1 SD
                        </option>

                        <option value="2 SD" {{ old('school_grade', $student->school_grade) == '2 SD' ? 'selected' : '' }}>
                            2 SD
                        </option>

                        <option value="3 SD" {{ old('school_grade', $student->school_grade) == '3 SD' ? 'selected' : '' }}>
                            3 SD
                        </option>

                        <option value="4 SD" {{ old('school_grade', $student->school_grade) == '4 SD' ? 'selected' : '' }}>
                            4 SD
                        </option>

                        <option value="5 SD" {{ old('school_grade', $student->school_grade) == '5 SD' ? 'selected' : '' }}>
                            5 SD
                        </option>

                        <option value="6 SD" {{ old('school_grade', $student->school_grade) == '6 SD' ? 'selected' : '' }}>
                            6 SD
                        </option>

                    </select>

                    @error('school_grade')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- KK --}}
                <div class="mb-6">

                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        KK
                    </label>

                    {{-- PREVIEW AREA --}}
                    <div class="flex flex-col gap-4 mb-4 md:flex-row">

                        {{-- FILE LAMA --}}
                        @if($student->kk_file)

                            @php
                                $kkUrl = asset('storage/' . $student->kk_file);
                                $kkExt = strtolower(pathinfo($student->kk_file, PATHINFO_EXTENSION));
                            @endphp

                            <div>

                                <p class="mb-2 text-xs font-semibold text-slate-500">
                                    File Lama
                                </p>

                                {{-- IMAGE --}}
                                @if(in_array($kkExt, ['jpg','jpeg','png']))

                                    <img src="{{ $kkUrl }}"
                                        onclick="openDocumentPreview('image', '{{ $kkUrl }}')"
                                        class="object-cover w-32 h-32 transition border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                {{-- PDF --}}
                                @elseif($kkExt === 'pdf')

                                    <div
                                        onclick="openDocumentPreview('pdf', '{{ $kkUrl }}')"
                                        class="flex flex-col items-center justify-center w-32 h-32 transition bg-red-100 border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                        <div class="text-4xl">
                                            📄
                                        </div>

                                        <p class="mt-2 text-xs font-semibold text-red-500">
                                            PDF
                                        </p>

                                    </div>

                                @endif

                            </div>

                        @endif

                        {{-- PREVIEW BARU --}}
                        <div id="preview_kk_wrapper"
                            class="hidden">

                            <p class="mb-2 text-xs font-semibold text-slate-500">
                                File Baru
                            </p>

                            {{-- IMAGE --}}
                            <img id="preview_kk"
                                onclick="openDocumentPreview('image', this.src)"
                                class="hidden object-cover w-32 h-32 transition border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                            {{-- PDF --}}
                            <div id="preview_kk_pdf"
                                onclick="openDocumentPreview('pdf', this.dataset.src)"
                                class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                <div class="text-4xl">
                                    📄
                                </div>

                                <p class="mt-2 text-xs font-semibold text-red-500">
                                    PDF
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- INPUT --}}
                    <input type="file"
                        name="kk_file"
                        onchange="previewDocument(event, 'kk')"
                        class="w-full p-2 border rounded-xl">

                    @error('kk_file')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- AKTA --}}
                <div class="mb-6">

                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        Akta
                    </label>

                    {{-- PREVIEW AREA --}}
                    <div class="flex flex-col gap-4 mb-4 md:flex-row">

                        {{-- FILE LAMA --}}
                        @if($student->birth_certificate_file)

                            @php
                                $aktaUrl = asset('storage/' . $student->birth_certificate_file);
                                $aktaExt = strtolower(pathinfo($student->birth_certificate_file, PATHINFO_EXTENSION));
                            @endphp

                            <div>

                                <p class="mb-2 text-xs font-semibold text-slate-500">
                                    File Lama
                                </p>

                                {{-- IMAGE --}}
                                @if(in_array($aktaExt, ['jpg','jpeg','png']))

                                    <img src="{{ $aktaUrl }}"
                                        onclick="openDocumentPreview('image', '{{ $aktaUrl }}')"
                                        class="object-cover w-32 h-32 transition border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                {{-- PDF --}}
                                @elseif($aktaExt === 'pdf')

                                    <div
                                        onclick="openDocumentPreview('pdf', '{{ $aktaUrl }}')"
                                        class="flex flex-col items-center justify-center w-32 h-32 transition bg-red-100 border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                        <div class="text-4xl">
                                            📄
                                        </div>

                                        <p class="mt-2 text-xs font-semibold text-red-500">
                                            PDF
                                        </p>

                                        <p class="mt-1 text-[10px] text-gray-500">
                                            Klik untuk preview
                                        </p>

                                    </div>

                                @endif

                            </div>

                        @endif

                        {{-- PREVIEW BARU --}}
                        <div id="preview_akta_wrapper"
                            class="hidden">

                            <p class="mb-2 text-xs font-semibold text-slate-500">
                                File Baru
                            </p>

                            {{-- IMAGE --}}
                            <img id="preview_akta"
                                onclick="openDocumentPreview('image', this.src)"
                                class="hidden object-cover w-32 h-32 transition border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                            {{-- PDF --}}
                            <div id="preview_akta_pdf"
                                onclick="openDocumentPreview('pdf', this.dataset.src)"
                                class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                <div class="text-4xl">
                                    📄
                                </div>

                                <p class="mt-2 text-xs font-semibold text-red-500">
                                    PDF
                                </p>

                                <p class="mt-1 text-[10px] text-gray-500">
                                    Klik untuk preview
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- INPUT --}}
                    <input type="file"
                        name="birth_certificate_file"
                        onchange="previewDocument(event, 'akta')"
                        class="w-full p-2 border rounded-xl">

                    @error('birth_certificate_file')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror  
                </div>

                <button class="shadow-sm btn-primary">
                    Update
                </button>
            </form>

        </div>

    </div>

    {{-- ================= DOCUMENT PREVIEW MODAL ================= --}}
    <div id="documentPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closeDocumentPreview()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="documentPreviewImage"
                class="hidden object-contain w-full max-h-[90vh] rounded-xl shadow-2xl">

            {{-- PDF --}}
            <iframe id="documentPreviewPdf"
                class="hidden w-full bg-white rounded-xl h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>
</x-app-layout>

<script>

    function previewDocument(event, type) {

        const file = event.target.files[0];

        if (!file) return;

        const wrapper = document.getElementById(`preview_${type}_wrapper`);
        const image = document.getElementById(`preview_${type}`);
        const pdf = document.getElementById(`preview_${type}_pdf`);

        wrapper.classList.remove('hidden');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (file.type.startsWith('image/')) {

            image.src = URL.createObjectURL(file);

            image.classList.remove('hidden');

        }

        // PDF
        else if (file.type === 'application/pdf') {

            pdf.dataset.src = URL.createObjectURL(file);

            pdf.classList.remove('hidden');
            pdf.classList.add('flex');

        }

    }

    function openDocumentPreview(type, src) {

        const modal = document.getElementById('documentPreviewModal');

        const image = document.getElementById('documentPreviewImage');
        const pdf = document.getElementById('documentPreviewPdf');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

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

        modal.style.display = 'flex';
    }

    function closeDocumentPreview() {

        const modal = document.getElementById('documentPreviewModal');

        document.getElementById('documentPreviewImage').src = '';
        document.getElementById('documentPreviewPdf').src = '';

        modal.style.display = 'none';
    }

    // klik backdrop
    document.getElementById('documentPreviewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'documentPreviewModal') {
                closeDocumentPreview();
            }

        });

</script>