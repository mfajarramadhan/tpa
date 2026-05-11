<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Detail Anak</h2>
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

        {{-- CARD --}}
        <div class="p-6 mx-auto max-w-7xl card-panel">

            {{-- HEADER --}}
            <div class="flex items-center gap-3 px-3 pb-5 border-b border-gray-100">

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">
                    <iconify-icon 
                        icon="heroicons:academic-cap-solid"
                        class="text-xl text-[var(--primary)]">
                    </iconify-icon>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Detail Anak
                    </h2>

                    <p class="text-sm text-slate-500">
                        Informasi detail anak
                    </p>
                </div>

            </div>

            {{-- CONTENT --}}
            <div class="divide-y divide-gray-100">

                {{-- NAMA --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Nama Lengkap
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">
                        {{ $student->name }}
                    </p>

                </div>

                {{-- KELAS --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Kelas
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">
                        {{ $student->classroom->name ?? '-' }}
                    </p>

                </div>

                {{-- EMAIL --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Email
                    </p>

                    <p class="text-sm font-bold text-right break-words text-slate-800">
                        {{ $student->user->email ?? '-' }}
                    </p>

                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Tanggal Lahir
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">

                        {{ $student->birth_date
                            ? \Carbon\Carbon::parse($student->birth_date)->format('d M Y')
                            : '-' }}

                    </p>

                </div>

                {{-- NISN --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        NISN
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">
                        {{ $student->nisn ?? '-' }}
                    </p>

                </div>

                {{-- GENDER --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Jenis Kelamin
                    </p>

                    <p class="text-sm font-bold text-right text-slate-800">

                        @if($student->gender == 'L')
                            Laki-laki
                        @elseif($student->gender == 'P')
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
                        {{ $student->school_origin ?? '-' }}
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

                {{-- STATUS --}}
                <div class="grid items-center grid-cols-2 gap-4 px-3 py-5">

                    <p class="text-sm font-semibold text-slate-500">
                        Status
                    </p>

                    <div class="flex justify-end">

                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold
                            {{ $student->status == 'aktif'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-600' }}">

                            <span class="w-2 h-2 rounded-full
                                {{ $student->status == 'aktif'
                                    ? 'bg-green-500'
                                    : 'bg-gray-400' }}">
                            </span>

                            {{ ucfirst($student->status) }}

                        </span>

                    </div>

                </div>

            </div>
        
            {{-- BUTTON --}}
            <div class="flex flex-wrap gap-3 mt-8">

                <a href="{{ route('students.edit', $student->id) }}"
                   class="shadow-sm btn-primary">

                    Edit

                </a>

            </div>
        </div>

    </div>

</x-app-layout>