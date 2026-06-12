<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Kelola User
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BACK BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('users.index', request()->query()) }}"
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
            <div class="flex items-center gap-3 px-3 pb-5 border-b border-custom">

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                    <iconify-icon
                        icon="heroicons:user-solid"
                        class="text-xl text-[var(--primary)]">
                    </iconify-icon>

                </div>

                <div>
                    @php
                        $roleLabels = [
                            'siswa' => 'Siswa',
                            'guru' => 'Guru',
                            'orang_tua' => 'Orang Tua',
                            'superadmin' => 'Superadmin',
                        ];
                    @endphp

                    <h2 class="text-xl font-bold text-[var(--text-main)]">
                        Detail User - {{ $roleLabels[$user->roles->first()->name ?? ''] ?? '-' }}
                    </h2>

                    <p class="text-sm text-[var(--text-tertiary)]">
                        Informasi detail user
                    </p>

                </div>

            </div>

            {{-- CONTENT --}}
            <div class="divide-y divide-gray-100">

                {{-- NAMA --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Nama
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">
                        {{ $user->name }}
                    </p>

                </div>

                @if($user->student)
                    {{-- ORANG TUA --}}
                    <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                        <p class="text-sm font-semibold text-slate-500">
                            Orang Tua
                        </p>

                        <p class="text-sm font-bold text-right text-slate-800">
                            {{ $user->student->parent->name }}
                        </p>

                    </div>
                @endif

                {{-- EMAIL --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Email
                    </p>

                    <p class="text-sm font-bold text-right break-words text-slate-800">
                        {{ $user->email }}
                    </p>

                </div>

                {{-- ALAMAT --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Alamat
                    </p>

                    <p class="text-sm font-bold text-right break-words text-slate-800">
                        {{ $user->student ? ($user->student->parent->address ?? '-') : ($user->address ?? '-') }}
                    </p>

                </div>

                
                {{-- ROLE --}}
                @php
                    $roleLabels = [
                        'siswa' => 'Siswa',
                        'guru' => 'Guru',
                        'orang_tua' => 'Orang Tua',
                        'superadmin' => 'Superadmin',
                    ];
                @endphp
                
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Role
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">
                        {{ $roleLabels[$user->role] ?? '-' }}
                    </p>

                </div>
                
                {{-- STATUS --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Status
                    </p>

                    <div class="flex justify-end">

                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold
                            {{ $user->status == 'aktif'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">

                            <span class="w-2 h-2 rounded-full
                                {{ $user->status == 'aktif'
                                    ? 'bg-green-500'
                                    : 'bg-red-500' }}">
                            </span>

                            {{ ucfirst($user->status) }}

                        </span>

                    </div>

                </div>

            </div>

            {{-- DATA SISWA --}}
            @if($user->student)

                <div class="mt-8 overflow-hidden border border-gray-100 rounded-2xl">

                    {{-- HEADER --}}
                    <div class="flex items-center gap-3 px-3 py-4 border-b border-gray-100 bg-gray-50">

                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">

                            <iconify-icon
                                icon="heroicons:academic-cap-solid"
                                class="text-xl text-blue-600">
                            </iconify-icon>

                        </div>

                        <div>

                            <h3 class="font-bold text-slate-800">
                                Data Siswa
                            </h3>

                            <p class="text-sm text-slate-500">
                                Informasi detail siswa
                            </p>

                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="divide-y divide-gray-100">

                        {{-- KELAS --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Kelas
                            </p>

                            <p class="text-sm font-bold text-right text-slate-800">
                                {{ $user->student->classroom->name ?? '-' }}
                            </p>

                        </div>

                        {{-- NISN --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                NISN
                            </p>

                            <p class="text-sm font-bold text-right text-slate-800">
                                {{ $user->student->nisn ?? '-' }}
                            </p>

                        </div>

                        {{-- NISN --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Tahun Akademik
                            </p>

                            <p class="text-sm font-bold text-right text-slate-800">
                                {{ $user->student->academicYear->name ?? '-' }}
                            </p>

                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Tanggal Lahir
                            </p>

                            <p class="text-sm font-bold text-right text-slate-800">

                                {{ $user->student->birth_date
                                    ? \Carbon\Carbon::parse($user->student->birth_date)->format('d M Y')
                                    : '-' }}

                            </p>

                        </div>

                        {{-- GENDER --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Jenis Kelamin
                            </p>

                            <p class="text-sm font-bold text-right text-slate-800">

                                @if($user->student->gender == 'L')
                                    Laki-laki
                                @elseif($user->student->gender == 'P')
                                    Perempuan
                                @else
                                    -
                                @endif

                            </p>

                        </div>

                        {{-- ASAL SEKOLAH --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Asal Sekolah
                            </p>

                            <p class="text-sm font-bold text-right break-words text-slate-800">
                                {{ $user->student->school_origin ?? '-' }}
                            </p>

                        </div>

                        {{-- KELAS DI SEKOLAH --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Kelas di Sekolah
                            </p>

                            <p class="text-sm font-bold text-right text-slate-800">
                                {{ $user->student->school_grade ?? '-' }}
                            </p>

                        </div>

                        {{-- KK --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Kartu Keluarga
                            </p>

                            <div class="flex justify-end">
                                @if($user->student->kk_file)

                                    @php
                                        $kkUrl = asset('storage/' . $user->student->kk_file);
                                        $kkExt = strtolower(pathinfo($user->student->kk_file, PATHINFO_EXTENSION));
                                    @endphp

                                    @if(in_array($kkExt, ['jpg', 'jpeg', 'png']))

                                        <img src="{{ $kkUrl }}"
                                            onclick="openDocumentPreview('image', '{{ $kkUrl }}')"
                                            class="object-cover w-24 h-24 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    @elseif($kkExt === 'pdf')

                                        <div onclick="openDocumentPreview('pdf', '{{ $kkUrl }}')"
                                            class="flex flex-col items-center justify-center w-24 h-24 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                            <div class="text-3xl">
                                                📄
                                            </div>

                                            <p class="mt-1 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                @else

                                    <span class="text-sm font-bold text-slate-800">
                                        -
                                    </span>

                                @endif
                            </div>

                        </div>

                        {{-- AKTA --}}
                        <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                            <p class="text-sm font-semibold text-slate-500">
                                Akta Kelahiran
                            </p>

                            <div class="flex justify-end">
                                @if($user->student->birth_certificate_file)

                                    @php
                                        $aktaUrl = asset('storage/' . $user->student->birth_certificate_file);
                                        $aktaExt = strtolower(pathinfo($user->student->birth_certificate_file, PATHINFO_EXTENSION));
                                    @endphp

                                    @if(in_array($aktaExt, ['jpg', 'jpeg', 'png']))

                                        <img src="{{ $aktaUrl }}"
                                            onclick="openDocumentPreview('image', '{{ $aktaUrl }}')"
                                            class="object-cover w-24 h-24 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    @elseif($aktaExt === 'pdf')

                                        <div onclick="openDocumentPreview('pdf', '{{ $aktaUrl }}')"
                                            class="flex flex-col items-center justify-center w-24 h-24 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                            <div class="text-3xl">
                                                📄
                                            </div>

                                            <p class="mt-1 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                @else

                                    <span class="text-sm font-bold text-slate-800">
                                        -
                                    </span>

                                @endif
                            </div>

                        </div>

                    </div>

                </div>

            @endif

            {{-- BUTTON --}}
            <div class="flex flex-wrap gap-3 mt-8">

                @if(!$user->trashed())

                    <a href="{{ route('users.edit', $user->id) . '?' . request()->getQueryString() }}"
                    class="shadow-sm btn-primary">

                        Edit

                    </a>

                @endif

            </div>

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
                class="hidden object-contain w-full max-h-[90vh] rounded-lg shadow-2xl">

            {{-- PDF --}}
            <iframe id="documentPreviewPdf"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>
</x-app-layout>

<script>

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