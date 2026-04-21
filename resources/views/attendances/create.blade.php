<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Absensi Siswa</h2>
    </x-slot>

    <div class="py-6">
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

            {{-- 🔹 FILTER KELAS & SESI --}}
            <form method="GET" action="{{ route('attendances.create') }}" class="mb-6">

                {{-- WAJIB: kirim classroom_id --}}
                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

                <div class="flex flex-wrap items-end gap-4">

                    {{-- INFO KELAS --}}
                    <div>
                        <div class="text-small">Kelas</div>
                        <div class="text-lg font-bold text-[var(--text-main)]">
                            {{ $classroom->name }}
                        </div>
                    </div>

                    {{-- PILIH SESI --}}
                    <div>
                        <label class="text-small">Sesi</label>

                        <select name="session"
                                class="input-solid"
                                onchange="this.form.submit()">

                            <option value="pagi" {{ $session == 'pagi' ? 'selected' : '' }}>
                                Pagi (08:00 - 10:15)
                            </option>

                            <option value="sore" {{ $session == 'sore' ? 'selected' : '' }}>
                                Sore (14:00 - 17:00)
                            </option>

                        </select>
                    </div>

                </div>

            </form>

            {{-- 🔥 FORM ABSENSI --}}
            @if($classroom->students->count() > 0)
                <form method="POST" action="{{ route('attendances.store') }}">
                    @csrf

                    <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                    <input type="hidden" name="session" value="{{ $session }}">

                    {{-- TANGGAL --}}
                    <div class="mb-4">
                        <label class="text-small">Tanggal</label>
                        <input type="date" name="date"
                               class="input-solid"
                               value="{{ date('Y-m-d') }}">
                    </div>

                    {{-- BUTTON HADIR SEMUA --}}
                    <div class="mt-4 overflow-x-auto card-panel">

                        <!-- ACTION -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold">Daftar Siswa</h3>

                            <button type="button"
                                    onclick="selectAllHadir()"
                                    class="text-sm btn-outline">
                                ✔ Semua Hadir
                            </button>
                        </div>

                        <table class="w-full text-sm table-custom">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Hadir</th>
                                    <th>Keterangan</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($classroom->students as $i => $student)
                                <tr>

                                    <!-- NO -->
                                    <td>{{ $i + 1 }}</td>

                                    <!-- NAMA -->
                                    <td class="font-semibold text-[var(--text-main)]">
                                        {{ $student->name }}
                                    </td>

                                    <!-- CHECKBOX HADIR -->
                                    <td class="text-center">
                                        <input type="checkbox"
                                            name="students[{{ $student->id }}][hadir]"
                                            class="w-4 h-4 hadir-checkbox"
                                            onchange="toggleStatus({{ $student->id }})"
                                            {{ isset($details[$student->id]) && $details[$student->id]->status == 'hadir' ? 'checked' : '' }}>
                                    </td>

                                    <!-- DROPDOWN -->
                                    <td>
                                        <select name="students[{{ $student->id }}][status]"
                                                id="status-{{ $student->id }}"
                                                class="text-sm input-solid">

                                            @php
                                                $status = $details[$student->id]->status ?? 'alpha';
                                            @endphp

                                            <option value="">--</option>
                                            <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="alpha" {{ $status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                        </select>
                                    </td>

                                    <!-- ALASAN -->
                                    <td>
                                        <input type="text"
                                            name="students[{{ $student->id }}][note]"
                                            id="note-{{ $student->id }}"
                                            class="text-sm input-solid"
                                            placeholder="Opsional..."
                                            value="{{ $details[$student->id]->note ?? '' }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="mt-4">
                        <button class="btn-primary" type="submit">
                            Simpan Absensi
                        </button>
                    </div>

                </form>
            @endif

        </div>
    </div>

    {{-- JS --}}
    <script>
        function toggleStatus(id) {
            const checkbox = document.querySelector(`input[name="students[${id}][hadir]"]`);
            const status = document.getElementById(`status-${id}`);
            const note = document.getElementById(`note-${id}`);

            if (checkbox.checked) {
                // HADIR
                note.value = '';

                status.value = ''; // 🔥 kosongkan dropdown
                status.disabled = true;

                note.disabled = true;
            } else {
                // TIDAK HADIR
                status.disabled = false;
                note.disabled = false;
            }
        }

        function selectAllHadir() {
            document.querySelectorAll('.hadir-checkbox').forEach(cb => {
                cb.checked = true;

                const id = cb.name.match(/\d+/)[0];

                const status = document.getElementById(`status-${id}`);
                const note = document.getElementById(`note-${id}`);

                note.value = '';

                status.value = ''; // 🔥 ini kunci
                status.disabled = true;

                note.disabled = true;
            });
        }
        </script>

</x-app-layout>