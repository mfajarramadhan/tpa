<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Absensi {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

            {{-- tombol --}}
            <button onclick="setAllHadir()" class="mb-4 btn-outline">
                ✔ Semua Hadir
            </button>

            <form method="POST" action="{{ route('attendances.store') }}">
                @csrf

                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

                <div class="overflow-x-auto card-panel">
                    <table class="w-full table-custom">

                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>

                                <td>
                                    <select name="students[{{ $student->id }}][status]"
                                        class="input-solid status-select"
                                        onchange="toggleNote(this)">
                                        
                                        <option value="hadir">Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="alpha">Alpha</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="text"
                                        name="students[{{ $student->id }}][note]"
                                        class="hidden input-solid note-field"
                                        placeholder="Alasan...">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <button class="mt-4 btn-primary">
                    Simpan Absensi
                </button>

            </form>

        </div>
    </div>
</x-app-layout>

<script>
    function setAllHadir() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.value = 'hadir';
            toggleNote(select);
        });
    }

    function toggleNote(select) {
        const row = select.closest('tr');
        const note = row.querySelector('.note-field');

        if (select.value === 'hadir') {
            note.classList.add('hidden');
            note.value = '';
        } else {
            note.classList.remove('hidden');
        }
    }
</script>