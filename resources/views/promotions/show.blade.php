<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Kenaikan Kelas
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BACK BUTTON --}}
        <div class="mb-6">
            <a href="{{ route('promotions.index') }}"
            class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>
        </div> 
        
        <div class="mx-auto max-w-7xl">

            {{-- INFO --}}
            <div class="mb-6">

                <div class="grid items-center w-full grid-cols-2 gap-6 md:grid-cols-3">

                    {{-- KELAS SAAT INI --}}
                    <div>

                        <label class="text-small">
                            Kelas Saat Ini
                        </label>

                        <div class="text-lg font-semibold text-[var(--text-main)]">
                            {{ $classroom->name }}
                        </div>

                    </div>

                    {{-- TOTAL --}}
                    <div class="text-right md:text-left">

                        <label class="text-small">
                            Total Siswa
                        </label>

                        <div class="text-lg font-semibold text-[var(--text-main)]">
                            {{ $students->count() }} siswa
                        </div>

                    </div>

                    {{-- TUJUAN --}}
                    <div class="col-span-2 md:col-span-1">

                        <label class="text-small">
                            Kenaikan
                        </label>

                        <div class="text-lg font-semibold text-[var(--primary)]">

                            {{ $classroom->name }}

                            →

                            {{ $nextClass }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- WARNING --}}
            <div class="p-4 mb-6 border rounded-2xl bg-[var(--warning-light)] border-[var(--warning)]">

                <div class="flex items-start gap-3">

                    <iconify-icon
                        icon="solar:danger-triangle-bold-duotone"
                        width="22"
                        class="text-[var(--warning)] mt-0.5">
                    </iconify-icon>

                    <div>

                        <h3 class="font-semibold text-[var(--warning)]">
                            Perhatian
                        </h3>

                        <p class="mt-1 text-sm text-[var(--text-main)]">
                            Lakukan kenaikan kelas dari tingkat tertinggi terlebih dahulu.
                        </p>

                    </div>

                </div>

            </div>

            {{-- FORM --}}
            @if($students->count() > 0)

            <form method="POST"
                action="{{ route('promotions.process', $classroom->id) }}"
                onsubmit="confirmAction(
                    event,
                    'Proses Kenaikan Kelas?',
                    'Data kenaikan kelas siswa akan diproses',
                    'Ya, Proses',
                    'question'
                )">

                @csrf

                {{-- TABLE --}}
                <div class="overflow-x-auto card-panel">

                    <div class="flex items-center justify-between m-5">

                        <h3 class="font-semibold">
                            Daftar Siswa
                        </h3>

                        <button type="button"
                            id="btn-toggle"
                            onclick="toggleAll()"
                            class="text-sm btn-outline">

                            ✔ Pilih Semua

                        </button>

                    </div>

                    <table class="w-full text-sm table-custom whitespace-nowrap">

                        <thead>

                            <tr>

                                <th class="w-12 pl-6 text-center">
                                    <input type="checkbox"
                                        id="master-checkbox"
                                        class="w-4 h-4 rounded cursor-pointer accent-[var(--primary)]"
                                        checked
                                        onchange="toggleMaster()">
                                </th>

                                <th>
                                    Nama Siswa
                                </th>

                                <th class="pr-6">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($students as $student)

                            <tr>

                                <td class="pl-6 text-center">

                                    <input type="checkbox"
                                        name="students[]"
                                        value="{{ $student->id }}"
                                        class="student-checkbox w-4 h-4 rounded cursor-pointer accent-[var(--primary)]"
                                        checked
                                        onchange="checkAllState()">

                                </td>

                                <td class="font-semibold text-[var(--text-main)]">

                                    {{ $student->name }}

                                </td>

                                <td class="pr-6">

                                    <span class="badge badge-success">

                                        <iconify-icon
                                            icon="solar:check-circle-bold-duotone">
                                        </iconify-icon>

                                        Aktif

                                    </span>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- SUBMIT --}}
                <div class="mt-4">

                    <button type="submit" class="btn-primary">

                        @if($nextClass == 'Alumni')

                            Proses Kelulusan

                        @else

                            Proses Kenaikan Kelas

                        @endif

                    </button>

                </div>

            </form>

            @endif

        </div>
    </div>

    {{-- JS --}}
    <script>

        let allSelected = true;

        function toggleAll()
        {
            allSelected = !allSelected;

            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = allSelected;
            });

            document.getElementById('master-checkbox').checked = allSelected;

            updateButton();
        }

        function toggleMaster()
        {
            const master = document.getElementById('master-checkbox');

            allSelected = master.checked;

            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = allSelected;
            });

            updateButton();
        }

        function checkAllState()
        {
            const all = document.querySelectorAll('.student-checkbox');
            const checked = document.querySelectorAll('.student-checkbox:checked');

            allSelected = all.length === checked.length;

            document.getElementById('master-checkbox').checked = allSelected;

            updateButton();
        }

        function updateButton()
        {
            const btn = document.getElementById('btn-toggle');

            btn.innerText = allSelected
                ? '✖ Batal Pilih Semua'
                : '✔ Pilih Semua';
        }

    </script>

</x-app-layout>