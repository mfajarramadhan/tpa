<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Data Siswa Ditolak
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto">

        <div class="overflow-x-auto card-panel">
            <table class="w-full text-sm table-custom">

                <thead>
                    <tr>
                        <th>Nama Anak</th>
                        <th>Orang Tua</th>
                        <th>NIK</th>
                        <th>Sekolah</th>
                        <th>Alasan Ditolak</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($students as $student)
                    <tr>

                        {{-- Nama Anak --}}
                        <td class="font-semibold text-[var(--text-main)]">
                            {{ $student->name }}
                        </td>

                        {{-- Orang Tua --}}
                        <td class="text-small">
                            {{ $student->parent->name }}
                        </td>

                        {{-- NIK --}}
                        <td class="font-mono text-xs text-[var(--text-tertiary)]">
                            {{ $student->nik }}
                        </td>

                        {{-- Sekolah --}}
                        <td>
                            {{ $student->school_origin }}
                        </td>

                        {{-- Alasan Ditolak --}}
                        <td>
                            <span class="badge badge-danger">
                                <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon>
                                {{ $student->reject_reason ?? '-' }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="flex justify-center">

                                <form method="POST" action="{{ route('students.destroy', $student->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Yakin hapus data ini?')"
                                            class="btn-icon bg-[var(--danger-light)] text-[var(--danger)] hover:bg-[var(--danger)] hover:text-white"
                                            title="Hapus">
                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" width="18"></iconify-icon>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-small">
                            Tidak ada data siswa ditolak
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>