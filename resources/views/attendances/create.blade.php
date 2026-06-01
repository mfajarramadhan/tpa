<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Absensi Siswa</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

            {{-- Alert --}}
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

            {{-- BUTTON --}}
            <div class="mb-6">

                <a href="{{ route('attendances.index') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                    <iconify-icon
                        icon="heroicons:arrow-left-20-solid"
                        width="20">
                    </iconify-icon>

                    Kembali

                </a>

            </div>

            {{-- 🔹 FILTER KELAS & SESI --}}
            <form method="GET" action="{{ route('attendances.create') }}" class="mb-6">

                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">

                <div class="grid items-start w-full grid-cols-2 gap-6 md:grid-cols-3">

                    {{-- INFO KELAS --}}
                    <div class="text-left">
                        <label class="text-small">Kelas</label>

                        <div class="flex items-center h-11 text-2xl font-semibold text-[var(--text-main)]">
                            {{ $classroom->name }}
                        </div>
                    </div>
                    
                    {{-- TANGGAL --}}
                    <div>
                        <label class="text-small">Tanggal</label>

                        <input type="date"
                            name="date"
                            value="{{ old('date', request('date', $date ?? now()->toDateString())) }}"
                            max="{{ now()->toDateString() }}"
                            onchange="resetSessionToPagiAndSubmit(this)"
                            class="w-full p-2 mt-1 border rounded-lg bg-[var(--card-bg)] text-[var(--text-main)] border-[var(--border-color)] focus:ring focus:ring-blue-200">
                            
                        @error('date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PILIH SESI --}}
                    <div class="col-span-2 md:col-span-1">
                        <label class="text-small">
                            Sesi
                        </label>

                        <select name="session"
                                class="w-full p-2 mt-1 border rounded-lg bg-[var(--card-bg)] text-[var(--text-main)] border-[var(--border-color)] focus:ring focus:ring-[var(--primary-light)]"
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
            <form method="POST"
                action="{{ route('attendances.store') }}"
                onsubmit="confirmAction(
                    event,
                    'Simpan Absensi?',
                    'Data absensi siswa akan disimpan',
                    'Ya, Simpan',
                    'success'
                )">
                @csrf

                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                <input type="hidden" name="session" value="{{ $session }}">
                <input type="hidden" name="date" value="{{ $date }}">

                @if($isPagiLockedBySore ?? false)
                    <div class="p-4 mb-6 border rounded-xl bg-red-500/15 border-red-500/20">
                        <p class="mb-1 text-sm font-semibold text-red-500">
                            Absensi sesi pagi tidak dapat diubah atau disimpan karena absensi sesi sore pada tanggal ini sudah final!
                        </p>
                    </div>
                @endif

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
                                        data-locked="{{ isset($lockedStudents[$student->id]) ? '1' : '0' }}"
                                        onchange="toggleStatus({{ $student->id }})"
                                        {{ isset($details[$student->id]) && $details[$student->id]->status == 'hadir' ? 'checked' : '' }}
                                        {{ isset($lockedStudents[$student->id]) ? 'disabled' : '' }}>
                                </td>

                                <td>
                                    @php
                                        $status = $details[$student->id]->status ?? 'alpha';
                                    @endphp

                                    <select name="students[{{ $student->id }}][status]"
                                        id="status-{{ $student->id }}"
                                        class="input-solid max-w-40 w-fit min-w-[150px] bg-[var(--surface)] rounded-xl py-2.5 text-sm"
                                        {{ isset($lockedStudents[$student->id]) ? 'disabled' : '' }}>

                                        <option value="">--</option>

                                        <option value="izin"
                                            {{ isset($details[$student->id]) && $details[$student->id]->status == 'izin' ? 'selected' : '' }}>
                                            Izin
                                        </option>

                                        <option value="sakit"
                                            {{ isset($details[$student->id]) && $details[$student->id]->status == 'sakit' ? 'selected' : '' }}>
                                            Sakit
                                        </option>

                                        <option value="alpha"
                                            {{ isset($details[$student->id]) && $details[$student->id]->status == 'alpha' ? 'selected' : '' }}>
                                            Alpha
                                        </option>
                                    </select>
                                </td>

                                <td class="pr-6">
                                    <input type="text"
                                        name="students[{{ $student->id }}][note]"
                                        id="note-{{ $student->id }}"
                                        class="text-sm input-solid"
                                        value="{{ $details[$student->id]->note ?? '' }}"
                                        {{ isset($lockedStudents[$student->id]) ? 'disabled' : '' }}>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- SUBMIT --}}
                <div class="flex justify-end mt-6">
                    <button type="submit"
                            id="submitBtn"
                            class="disabled:opacity-50 disabled:cursor-not-allowed btn-primary {{ ($isPagiLockedBySore ?? false) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ ($isPagiLockedBySore ?? false) ? 'disabled' : '' }}>
                        Simpan Absensi
                    </button>
                </div>

            </form>
            @endif

        </div>
    </div>

</x-app-layout> 
<script>
    let allHadirActive = false;

    // init saat load
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.hadir-checkbox').forEach(cb => {
            const id = cb.name.match(/\d+/)[0];
            applyStatusUI(id);
        });

        checkAllState();
    });

    // handle UI (core function)
    function applyStatusUI(id) {
        const checkbox = document.querySelector(`input[name="students[${id}][hadir]"]`);
        const status = document.getElementById(`status-${id}`);
        const note = document.getElementById(`note-${id}`);

        const isLocked = checkbox.dataset.locked === '1';

        // jika locked tidak bisa dirubah
        if (isLocked) {
            status.disabled = true;
            note.disabled = true;
            return;
        }

        if (checkbox.checked) {
            // hadir
            status.value = '';
            note.value = '';

            status.disabled = true;
            note.disabled = true;
        } else {
            // tidak hadir
            status.disabled = false;
            note.disabled = false;
        }
    }

    // toggle per siswa
    function toggleStatus(id) {
        applyStatusUI(id);
        checkAllState();
    }

    // toggle semua hadir
    function toggleAllHadir() {
        const btn = document.getElementById('btn-toggle-hadir');

        allHadirActive = !allHadirActive;

        document.querySelectorAll('.hadir-checkbox').forEach(cb => {
            const isLocked = cb.dataset.locked === '1';

            if (isLocked) return; // skip siswa yang sudah diabsen pagi

            cb.checked = allHadirActive;

            const id = cb.name.match(/\d+/)[0];
            applyStatusUI(id);
        });

        updateButtonText(btn);
    }

    // update button semua hadir
    function updateButtonText(btn) {
        btn.innerText = allHadirActive
            ? '✖ Batal Semua Hadir'
            : '✔ Semua Hadir';
    }

    // auto detect state/kondisi
    function checkAllState() {
        const all = document.querySelectorAll('.hadir-checkbox:not([data-locked="1"])');
        const checked = document.querySelectorAll('.hadir-checkbox:not([data-locked="1"]):checked');
        const btn = document.getElementById('btn-toggle-hadir');

        if (!btn) return;

        if (all.length > 0 && all.length === checked.length) {
            allHadirActive = true;
        } else {
            allHadirActive = false;
        }

        updateButtonText(btn);
    }

    // disabled button saat submit
    document.querySelector('form').addEventListener('submit', function () {

        const btn = document.getElementById('submitBtn');

        btn.disabled = true;

        btn.innerHTML = `     
            <span class="animate-pulse">
                Menyimpan...
            </span>
        `;
    });


    // sesi kembali ke pagi saat ganti tanggal
    function resetSessionToPagiAndSubmit(input) {

        const form = input.form;

        const sessionSelect = form.querySelector(
            'select[name="session"]'
        );

        if (sessionSelect) {
            sessionSelect.value = 'pagi';
        }

        form.submit();
    }
</script>

