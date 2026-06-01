<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Pendaftaran Ditolak
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">
 
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

            <div class="overflow-x-auto card-panel">

                <table class="w-full text-sm table-custom">

                    <thead>

                        <tr>

                            <th class="w-[32%]">
                                Nama
                            </th>

                            <th class="w-[24%]">
                                Asal Sekolah
                            </th>

                            <th class="w-[20%]">
                                Tanggal Lahir
                            </th>

                            <th class="w-[14%]">
                                Status
                            </th>

                            <th class="w-[10%] !text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($students as $student)

                            <tr>

                                @php
                                    $initial = strtoupper(substr($student->name, 0, 2));
                                @endphp

                                {{-- NAMA --}}
                                <td>

                                    <div class="flex items-center gap-4">

                                        {{-- AVATAR --}}
                                        <div class="w-10 h-10 rounded-full bg-[var(--primary-light)] text-[var(--primary)] flex items-center justify-center font-bold text-sm border border-[var(--primary-light)]">

                                            {{ $initial }}

                                        </div>

                                        {{-- INFO --}}
                                        <div>

                                            <div class="font-semibold text-[var(--text-main)]">
                                                {{ $student->name }}
                                            </div>

                                            <div class="text-xs text-[var(--text-tertiary)] mt-0.5">

                                                {{ $student->school_grade }}

                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- ASAL SEKOLAH --}}
                                <td class="text-small">

                                    {{ $student->school_origin }}

                                </td>

                                {{-- TANGGAL LAHIR --}}
                                <td class="text-small">

                                    {{ \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d F Y') }}

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    <span class="badge badge-danger">

                                        <iconify-icon
                                            icon="solar:close-circle-bold-duotone">
                                        </iconify-icon>

                                        Ditolak

                                    </span>

                                </td>

                                {{-- AKSI --}}
                                <td>

                                    <div class="flex justify-center">

                                        <a href="{{ route('approval.students.rejected.show', $student->id) }}"
                                            class="btn-icon border border-[var(--primary)] hover:border-[var(--primary)]"
                                            title="Detail">

                                            <iconify-icon
                                                icon="solar:eye-bold-duotone"
                                                width="18"
                                                class="text-[var(--primary)]">
                                            </iconify-icon>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="py-6 text-center text-small">

                                    Belum ada pendaftaran siswa ditolak!

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-app-layout>