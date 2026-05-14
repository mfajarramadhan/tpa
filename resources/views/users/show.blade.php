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
            <div class="flex items-center gap-3 px-3 pb-5 border-b border-gray-100">

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                    <iconify-icon
                        icon="heroicons:user-solid"
                        class="text-xl text-[var(--primary)]">
                    </iconify-icon>

                </div>

                <div>

                    <h2 class="text-xl font-bold text-slate-800">
                        Detail User
                    </h2>

                    <p class="text-sm text-slate-500">
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
                        {{ $user->address ?? '-' }}
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
                                {{ $student->school_grade ?? '-' }}
                            </p>

                        </div>

                    </div>

                </div>

            @endif

            {{-- BUTTON --}}
            <div class="flex flex-wrap gap-3 mt-8">

                <a href="{{ route('users.edit', $user->id) }}"
                   class="shadow-sm btn-primary">

                    Edit

                </a>

            </div>

        </div>

    </div>

</x-app-layout>