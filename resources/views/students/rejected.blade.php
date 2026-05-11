<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Pendaftaran Ditolak
        </h2>
    </x-slot>

    <div class="py-6 mx-auto md:py-0 max-w-7xl">

        <div class="overflow-x-auto card-panel">
            <table class="w-full text-sm table-custom">

                <thead>
                    <tr>
                        <th class="w-[20%]">Nama Anak</th>
                        <th class="w-[18%]">Orang Tua</th>
                        <th class="w-[14%]">NISN</th>
                        <th class="w-[18%]">Sekolah Asal</th>
                        <th class="w-[20%]">Alasan Ditolak</th>
                        <th class="w-[10%] !text-center">Aksi</th>
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

                        {{-- NISN --}}
                        <td class="font-mono text-xs text-[var(--text-tertiary)]">
                            {{ $student->nisn }}
                        </td>

                        {{-- Sekolah --}}
                        <td class="max-w-[220px]">

                            <p class="line-clamp-2"
                            title="{{ $student->school_origin }}">

                                {{ $student->school_origin }}

                            </p>

                        </td>

                        {{-- Alasan Ditolak --}}
                        <td class="max-w-[240px]">

                            @if($student->reject_reason)

                                <p class="text-sm text-[var(--danger)] line-clamp-3 cursor-help"
                                title="{{ $student->reject_reason }}">

                                    {{ $student->reject_reason }}

                                </p>

                            @else

                                <span class="text-small">
                                    -
                                </span>

                            @endif

                        </td>

                        {{-- Aksi --}}
                        <td>

                            <div class="flex justify-center">

                                <form method="POST"
                                    action="{{ route('students.destroy', $student->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Yakin hapus data ini?')"
                                            title="Hapus"
                                            class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]">

                                        <iconify-icon icon="heroicons:trash"
                                                    class="text-[var(--danger)] group-hover:text-white">
                                        </iconify-icon>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="py-10 text-center text-small">

                            Tidak ada data siswa ditolak

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>