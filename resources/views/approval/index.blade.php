<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Approval Pendaftaran
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

                            <th class="w-[14%]">
                                Tanggal Lahir
                            </th>

                            <th class="w-[20%]">
                                Tanggal Pendaftaran 
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

                                {{-- TANGGAL PENDAFTARAN --}}
                                <td class="text-small">

                                    {{ \Carbon\Carbon::parse($student->created_at)->format('d-m-Y | H:i') }} WIB

                                </td>

                                {{-- AKSI --}}
                                <td>

                                    <div class="flex justify-center">

                                        <a href="{{ route('approval.students.show', $student->id) }}"
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

                                    Belum ada pendaftaran siswa baru

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>
                {{-- Pagination --}}
                @if($students->hasPages())

                    <div class="flex items-center justify-between p-5 border-t border-[var(--border-light)] bg-[var(--surface)]">

                        {{-- Info --}}
                        <div class="text-sm font-medium text-[var(--text-tertiary)]">

                            Menampilkan
                            {{ $students->firstItem() ?? 0 }}
                            -
                            {{ $students->lastItem() ?? 0 }}

                            dari

                            {{ $students->total() }}

                            data

                        </div>

                        {{-- Button --}}
                        <div class="flex gap-2">

                            {{-- Prev --}}
                            @if($students->onFirstPage())

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    &laquo; Prev
                                </span>

                            @else

                                <a href="{{ $students->previousPageUrl() }}"
                                class="px-3 py-1.5 text-sm border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    &laquo; Prev

                                </a>

                            @endif

                            {{-- Number --}}
                            @foreach($students->getUrlRange(1, $students->lastPage()) as $page => $url)

                                @if($page == $students->currentPage())

                                    <span class="px-3.5 py-1.5 text-sm shadow-md btn-primary">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $url }}"
                                    class="px-3.5 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                        {{ $page }}

                                    </a>

                                @endif

                            @endforeach

                            {{-- Next --}}
                            @if($students->hasMorePages())

                                <a href="{{ $students->nextPageUrl() }}"
                                class="px-3 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    Next &raquo;

                                </a>

                            @else

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    Next &raquo;
                                </span>

                            @endif

                        </div>

                    </div>

                @endif
            </div>

        </div>
    </div>

</x-app-layout>