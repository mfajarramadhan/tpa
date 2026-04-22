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

                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

                <div class="grid items-center w-full grid-cols-2 gap-6 md:grid-cols-3">
                    
                    {{-- TANGGAL --}}
                    <div>
                        <label class="text-small">Tanggal</label>
                        <div class="text-lg font-semibold text-[var(--text-main)]">
                            {{ now()->format('d M Y') }}
                        </div>
                    </div>

                    {{-- INFO KELAS --}}
                    <div class="text-right md:text-left">
                        <label class="text-small">Kelas</label>
                        <div class="text-lg font-semibold text-[var(--text-main)]">
                            {{ $classroom->name }}
                        </div>
                    </div>

                    {{-- PILIH SESI --}}
                    <div class="col-span-2 md:col-span-1">
                        <select name="session"
                                class="input-solid w-full bg-[var(--primary)] text-center rounded-xl py-2.5 text-sm"
                                onchange="this.form.submit()">

                            <option value="pagi" {{ $session == 'pagi' ? 'selected' : '' }}>
                                PAGI (08:00 WIB - 10:15 WIB)
                            </option>

                            <option value="sore" {{ $session == 'sore' ? 'selected' : '' }}>
                                SORE (14:00 WIB - 17:00 WIB)
                            </option>

                        </select>
                    </div>

                </div>

            </form>

            {{-- FORM ABSENSI --}}
            @if($classroom->students->count() > 0)
            <form method="POST" action="{{ route('attendances.store') }}">
                @csrf

                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                <input type="hidden" name="session" value="{{ $session }}">
                <input type="hidden" name="date" value="{{ now()->toDateString() }}">

                {{-- TABLE --}}
                <div class="mt-4 overflow-x-auto card-panel">

                    <div class="flex items-center justify-between m-5">
                        <h3 class="font-semibold">Daftar Siswa</h3>

                        <button type="button"
                                id="btn-toggle-hadir"
                                onclick="toggleAllHadir()"
                                class="text-sm btn-outline">
                            ✔ Semua Hadir
                        </button>
                    </div>

                    <table class="w-full text-sm table-custom whitespace-nowrap min-w-[1000px]">
                        <thead>
                            <tr>
                                <th class="w-12 pl-6 text-center">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">Hadir</th>
                                <th>Keterangan</th>
                                <th class="pr-6">Alasan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($classroom->students as $i => $student)
                            <tr>

                                <td class="pl-6 text-center">{{ $i + 1 }}</td>

                                <td class="font-semibold text-[var(--text-main)]">
                                    {{ $student->name }}
                                </td>

                                <td class="text-center">
                                    <input type="checkbox"
                                        name="students[{{ $student->id }}][hadir]"
                                        class="hadir-checkbox accent-[var(--primary)] w-4 h-4 rounded cursor-pointer"
                                        onchange="toggleStatus({{ $student->id }})"
                                        {{ isset($details[$student->id]) && $details[$student->id]->status == 'hadir' ? 'checked' : '' }}>
                                </td>

                                <td>
                                    @php
                                        $status = $details[$student->id]->status ?? 'alpha';
                                    @endphp

                                    <select name="students[{{ $student->id }}][status]"
                                            id="status-{{ $student->id }}"
                                            class="input-solid max-w-40 w-fit min-w-[150px] bg-[var(--surface)] rounded-xl py-2.5 text-sm">

                                        <option value="">--</option>
                                        <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="alpha" {{ $status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                    </select>
                                </td>

                                <td class="pr-6">
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
        let allHadirActive = false;

        // INIT SAAT LOAD (SYNC UI DENGAN DATA)
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.hadir-checkbox').forEach(cb => {
                const id = cb.name.match(/\d+/)[0];
                toggleStatus(id);
            });

            checkAllState(); // set tombol awal
        });

        // TOGGLE PER SISWA
        function toggleStatus(id) {
            const checkbox = document.querySelector(`input[name="students[${id}][hadir]"]`);
            const status = document.getElementById(`status-${id}`);
            const note = document.getElementById(`note-${id}`);

            if (checkbox.checked) {
                // ✔ HADIR
                status.value = ''; 
                note.value = '';

                status.disabled = true;
                note.disabled = true;
            } else {
                // ❌ TIDAK HADIR
                status.disabled = false;
                note.disabled = false;
            }

            checkAllState(); // update tombol
        }

        // TOGGLE SEMUA HADIR (BUTTON)
        function toggleAllHadir() {
            const btn = document.getElementById('btn-toggle-hadir');

            if (!allHadirActive) {
                // ✔ SET SEMUA HADIR
                document.querySelectorAll('.hadir-checkbox').forEach(cb => {
                    cb.checked = true;

                    const id = cb.name.match(/\d+/)[0];
                    toggleStatus(id);
                });

                btn.innerText = '✖ Batalkan';
                allHadirActive = true;

            } else {
                // ❌ RESET SEMUA
                document.querySelectorAll('.hadir-checkbox').forEach(cb => {
                    cb.checked = false;

                    const id = cb.name.match(/\d+/)[0];
                    toggleStatus(id);
                });

                btn.innerText = '✔ Semua Hadir';
                allHadirActive = false;
            }
        }

        // AUTO DETECT (JIKA SEMUA SUDAH DICENTANG MANUAL)
        function checkAllState() {
            const all = document.querySelectorAll('.hadir-checkbox');
            const checked = document.querySelectorAll('.hadir-checkbox:checked');
            const btn = document.getElementById('btn-toggle-hadir');

            if (!btn) return;

            if (all.length > 0 && all.length === checked.length) {
                allHadirActive = true;
                btn.innerText = '✖ Batalkan';
            } else {
                allHadirActive = false;
                btn.innerText = '✔ Semua Hadir';
            }
        }
        </script>

</x-app-layout>