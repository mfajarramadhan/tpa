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

            <div class="overflow-hidden bg-white rounded-lg shadow">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="transition hover:bg-gray-50">
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Kelas</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            <tr class="border-t">
                                <td class="p-3">{{ $student->name }}</td>
                                <td class="p-3">{{ $student->classroom->name ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-white
                                        {{ $student->status == 'aktif' ? 'bg-green-500' : 'bg-gray-500' }}">
                                        {{ $student->status }}
                                    </span>
                                </td>
                                <td class="flex gap-2 p-3">
                                    <a href="{{ route('attendance.student', $student->id) }}"
                                        class="px-3 py-1 text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Rekap Absensi
                                    </a>

                                    <a href="{{ route('students.show', $student->id) }}"
                                        class="px-3 py-1 text-white bg-gray-600 rounded hover:bg-gray-700">
                                            Lihat
                                        </a>

                                    <a href="{{ route('students.edit', $student->id) }}"
                                       class="px-3 py-1 text-white bg-yellow-400 rounded">
                                        Edit
                                    </a>

                                    <form action="{{ route('students.destroy', $student->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 text-white bg-red-500 rounded">
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr class="transition hover:bg-gray-50">
                                <td colspan="4" class="p-3 text-center text-gray-500">
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