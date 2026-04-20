<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Data Anak</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            <div class="mb-4">
                <a href="{{ route('students.create') }}"
                   class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    + Tambah Anak
                </a>
            </div>

            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Asal Sekolah</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
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
                                    {{ $student->school_origin ?? '-' }}
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
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Yakin hapus data ini?')"
                                                class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]"
                                                title="Hapus">
                                                    <iconify-icon icon="heroicons:trash" width="18" class="text-[var(--danger)] group-hover:text-white transition"></iconify-icon>
                                            </button>
                                        </form>

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