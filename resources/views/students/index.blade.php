<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Data Anak</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

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

            <div class="mb-4">
                <a href="{{ route('students.create') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">
                    <iconify-icon icon="solar:user-plus-bold-duotone" width="20"></iconify-icon>
                    Tambah Anak
                </a>
            </div>

            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th class="w-[36%]">Nama</th>
                            <th class="w-[36%]">Email</th>
                            <th class="w-[14%]">Status</th>
                            <th class="w-[14%] !text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr>

                                @php
                                    $initial = strtoupper(substr($student->name, 0, 2));
                                @endphp

                                <td>
                                    <div class="flex items-center gap-4">

                                        {{-- Avatar Inisial --}}
                                        <div class="w-10 h-10 rounded-full bg-[var(--primary-light)] text-[var(--primary)] flex items-center justify-center font-bold text-sm border border-[var(--primary-light)]">
                                            {{ $initial }}
                                        </div>

                                        {{-- Nama + Info kecil --}}
                                        <div>
                                            <div class="font-semibold text-[var(--text-main)]">
                                                {{ $student->name }}
                                            </div>
                                            <div class="text-xs text-[var(--text-tertiary)] mt-0.5">
                                                {{ $student->classroom->name ?? '-' }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                {{-- Asal Sekolah --}}
                                <td class="text-small">
                                    {{ $student->user->email ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge {{ $student->status == 'aktif' ? 'badge-success' : 'badge-info' }}">
                                        <iconify-icon icon="{{ $student->status == 'aktif' ? 'solar:check-circle-bold-duotone' : 'solar:info-circle-bold-duotone' }}"></iconify-icon>
                                        {{ $student->status }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    <div class="flex justify-center gap-2">

                                        {{-- Rekap Absensi --}}
                                        <a href="{{ route('attendance.student', $student->id) }}"
                                        class="btn-icon bg-[var(--info-light)] text-[var(--info)] hover:bg-[var(--info)] hover:text-white"
                                        title="Rekap Absensi">
                                            <iconify-icon icon="solar:calendar-bold-duotone" width="18"></iconify-icon>
                                        </a>

                                        {{-- Detail --}}
                                        <a href="{{ route('students.show', $student->id) }}"
                                        class="btn-icon border border-[var(--primary)] hover:border-[var(--primary)]"
                                        title="Detail">
                                            <iconify-icon icon="solar:eye-bold-duotone" width="18" class="text-[var(--primary)]"></iconify-icon>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('students.edit', $student->id) }}"
                                        class="btn-icon group bg-[var(--warning-light)] border border-[var(--warning-dark)] hover:bg-[var(--warning-dark)]"
                                        title="Edit">
                                            <iconify-icon icon="heroicons:pencil-square" width="18" class="text-[var(--warning-dark)] group-hover:text-white transition"></iconify-icon>                                        
                                        </a>

                                        {{-- Delete --}}
                                        {{-- <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Yakin hapus data ini?')"
                                                class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]"
                                                title="Hapus">
                                                    <iconify-icon icon="heroicons:trash" width="18" class="text-[var(--danger)] group-hover:text-white transition"></iconify-icon>
                                            </button>
                                        </form> --}}

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-small">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</x-app-layout>