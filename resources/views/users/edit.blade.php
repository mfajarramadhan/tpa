<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Kelola User
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('users.index') }}"
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

            {{-- HEADER --}}
            <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                    <iconify-icon
                        icon="heroicons:user-solid"
                        class="text-xl text-[var(--primary)]">
                    </iconify-icon>

                </div>

                <div>

                    <h2 class="text-xl font-bold text-[var(--text-main)]">
                        Edit User
                    </h2>

                    <p class="text-sm text-[var(--text-tertiary)]">
                        Perbarui informasi detail user
                    </p>

                </div>

            </div>

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Nama
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    @error('email')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                @if(!$user->student)

                    {{-- PHONE --}}
                    <div class="mb-4">

                        <label class="block mb-1 text-sm font-semibold">
                            Nomor Telepon
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="08xxxxxxxxxx"
                               maxlength="13"
                               inputmode="numeric"
                               pattern="[0-9]{10,13}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                               required>

                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- ALAMAT --}}
                    <div class="mb-4">

                        <label class="block mb-1 text-sm font-semibold">
                            Alamat
                        </label>

                        <textarea name="address"
                                  rows="3"
                                  class="w-full p-2 text-sm border rounded-lg resize-none focus:ring focus:ring-blue-200">{{ old('address', $user->address) }}</textarea>

                        @error('address')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                @endif

                {{-- PASSWORD --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Password Baru
                    </label>

                    <input type="text"
                           name="password"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                    <p class="mt-1 text-xs text-gray-500">
                        Kosongkan jika tidak ingin mengubah password
                    </p>

                    @error('password')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- DATA SISWA --}}
                @if($user->student)

                    <div class="p-5 mt-8 border border-gray-100 rounded-2xl bg-gray-50">

                        {{-- HEADER --}}
                        <div class="flex items-center gap-3 mb-5">

                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">

                                <iconify-icon
                                    icon="heroicons:academic-cap-solid"
                                    class="text-xl text-blue-600">
                                </iconify-icon>

                            </div>

                            <div>

                                <h3 class="font-bold text-slate-800">
                                    Edit Siswa
                                </h3>

                                <p class="text-sm text-slate-500">
                                    Perbarui informasi detail siswa
                                </p>

                            </div>

                        </div>

                        {{-- KELAS --}}
                        <div class="mb-4">

                            <label class="block mb-1 text-sm font-semibold">
                                Kelas
                            </label>

                            <select name="classroom_id"
                                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                                @foreach($classrooms as $class)

                                    <option value="{{ $class->id }}"
                                        {{ old('classroom_id', $user->student->classroom_id) == $class->id ? 'selected' : '' }}>

                                        {{ $class->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('classroom_id')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- NISN --}}
                        <div class="mb-4">

                            <label class="block mb-1 text-sm font-semibold">
                                NISN (Nomor Induk Siswa Nasional)
                            </label>

                            <input type="text"
                                name="nisn"
                                maxlength="10"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="{{ old('nisn', $user->student->nisn) }}"
                                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                            @error('nisn')
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
                                value="{{ old('birth_date', $user->student->birth_date) }}"
                                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- GENDER --}}
                        <div class="mb-4">

                            <label class="block mb-1 text-sm font-semibold">
                                Gender
                            </label>

                            <select name="gender"
                                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                                <option value="L"
                                    {{ old('gender', $user->student->gender) == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    {{ old('gender', $user->student->gender) == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>

                            </select>

                            @error('gender')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- ASAL SEKOLAH --}}
                        <div class="mb-2">

                            <label class="block mb-1 text-sm font-semibold">
                                Asal Sekolah
                            </label>

                            <input type="text"
                                name="school_origin"
                                value="{{ old('school_origin', $user->student->school_origin) }}"
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

                                <option value="1 SD"
                                    {{ old('school_grade', $user->student->school_grade) == '1 SD' ? 'selected' : '' }}>
                                    1 SD
                                </option>

                                <option value="2 SD"
                                    {{ old('school_grade', $user->student->school_grade) == '2 SD' ? 'selected' : '' }}>
                                    2 SD
                                </option>

                                <option value="3 SD"
                                    {{ old('school_grade', $user->student->school_grade) == '3 SD' ? 'selected' : '' }}>
                                    3 SD
                                </option>

                                <option value="4 SD"
                                    {{ old('school_grade', $user->student->school_grade) == '4 SD' ? 'selected' : '' }}>
                                    4 SD
                                </option>

                                <option value="5 SD"
                                    {{ old('school_grade', $user->student->school_grade) == '5 SD' ? 'selected' : '' }}>
                                    5 SD
                                </option>

                                <option value="6 SD"
                                    {{ old('school_grade', $user->student->school_grade) == '6 SD' ? 'selected' : '' }}>
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
                                @if($user->student->kk_file)

                                    @php
                                        $kkUrl = asset('storage/' . $user->student->kk_file);
                                        $kkExt = strtolower(pathinfo($user->student->kk_file, PATHINFO_EXTENSION));
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
                                class="w-full p-2 border rounded-xl @error('kk_file') border-red-500 @enderror">

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
                                @if($user->student->birth_certificate_file)

                                    @php
                                        $aktaUrl = asset('storage/' . $user->student->birth_certificate_file);
                                        $aktaExt = strtolower(pathinfo($user->student->birth_certificate_file, PATHINFO_EXTENSION));
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
                                class="w-full p-2 border rounded-xl @error('birth_certificate_file') border-red-500 @enderror">

                            @error('birth_certificate_file')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                @endif

                {{-- BUTTON --}}
                <div class="mt-8">

                    <button class="shadow-sm btn-primary">
                        Update
                    </button>

                </div>

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