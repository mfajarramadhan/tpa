<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Data Siswa Ditolak
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto">

        <div class="overflow-hidden bg-white rounded shadow">
            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nama Anak</th>
                        <th class="p-3">Orang Tua</th>
                        <th class="p-3">NIK</th>
                        <th class="p-3">Sekolah</th>
                        <th class="p-3">Alasan Ditolak</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($students as $student)
                    <tr class="border-t">

                        <td class="p-3">{{ $student->name }}</td>
                        <td class="p-3">{{ $student->parent->name }}</td>
                        <td class="p-3">{{ $student->nik }}</td>
                        <td class="p-3">{{ $student->school_origin }}</td>

                        <td class="p-3 text-red-600">
                            {{ $student->reject_reason ?? '-' }}
                        </td>

                        <td class="p-3 text-center">
                            <form method="POST" action="{{ route('students.destroy', $student->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-red-600 underline">
                                    Hapus
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">
                            Tidak ada data siswa ditolak
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>