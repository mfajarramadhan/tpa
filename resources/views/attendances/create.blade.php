<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Absensi Siswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto">

            {{-- 🔹 PILIH KELAS --}}
            <form method="GET" class="mb-6">
                <div class="flex gap-4">
                    <select name="classroom_id" class="px-3 py-2 border rounded">
                        <option value="">Pilih Kelas</option>
                        @foreach($classrooms as $class)
                            <option value="{{ $class->id }}"
                                {{ request('classroom_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>

                    <button class="px-4 py-2 text-white bg-blue-600 rounded">
                        Tampilkan
                    </button>
                </div>
            </form>

            {{-- 🔥 FORM ABSENSI --}}
            @if(count($students) > 0)
                <form method="POST" action="{{ route('attendances.store') }}">
                    @csrf

                    <input type="hidden" name="classroom_id" value="{{ request('classroom_id') }}">

                    <div class="mb-4">
                        <label>Tanggal</label>
                        <input type="date" name="date"
                               class="px-3 py-2 border rounded"
                               value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- 🔥 BUTTON HADIR SEMUA --}}
                    <div class="mb-4">
                        <button type="button"
                                onclick="checkAll()"
                                class="px-4 py-2 text-white bg-green-600 rounded">
                            Hadir Semua
                        </button>
                    </div>

                    {{-- 🔥 TABLE --}}
                    <div class="overflow-hidden bg-white rounded shadow">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3">Nama</th>
                                    <th class="p-3">Hadir</th>
                                    <th class="p-3">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($students as $student)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="p-3">{{ $student->name }}</td>

                                        <td class="p-3 text-center">
                                            <input type="checkbox"
                                                   class="hadir-checkbox"
                                                   name="students[{{ $student->id }}][hadir]">
                                        </td>

                                        <td class="p-3">
                                            <select name="students[{{ $student->id }}][status]"
                                                    class="px-2 py-1 border rounded">
                                                <option value="sakit">Sakit</option>
                                                <option value="izin">Izin</option>
                                                <option value="alpha">Alpha</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <button class="px-6 py-2 text-white bg-blue-600 rounded">
                            Simpan Absensi
                        </button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    <script>
        function checkAll() {
            document.querySelectorAll('.hadir-checkbox').forEach(cb => {
                cb.checked = true;

                // disable dropdown
                let select = cb.closest('tr').querySelector('select');
                if (select) select.disabled = true;
            });
        }

        // event manual checkbox
        document.querySelectorAll('.hadir-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                let select = this.closest('tr').querySelector('select');

                if (this.checked) {
                    select.disabled = true;
                } else {
                    select.disabled = false;
                }
            });
        });
    </script>

</x-app-layout>