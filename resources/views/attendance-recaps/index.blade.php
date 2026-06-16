<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Rekap Absensi
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

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

        <div class="mx-auto space-y-6 max-w-7xl">

            {{-- TAB --}}
            <div class="flex flex-wrap gap-3 mb-6">

                {{-- HARIAN --}}
                <a href="{{ route('attendance.recap', [
                        'tab' => 'daily',
                        'date' => request('date'),
                        'classroom_id' => request('classroom_id')
                    ]) }}"

                    class="flex items-center gap-2 px-4 py-2 shadow-sm rounded-xl transition-all duration-200

                    {{ request('tab', 'daily') == 'daily'
                        ? 'btn-primary'
                        : 'bg-surface border border-custom text-[var(--text-main)]
                        hover:border-[var(--primary)]
                        hover:bg-[var(--primary-light)]
                        hover:text-[var(--primary)]' }}">

                    {{-- ICON --}}
                    <iconify-icon
                        icon="solar:calendar-date-bold-duotone"
                        width="20">
                    </iconify-icon>

                    {{-- TEXT --}}
                    <span class="text-sm font-semibold">
                        Harian
                    </span>

                </a>

                {{-- BULANAN --}}
                <a href="{{ route('attendance.recap', [
                        'tab' => 'monthly',
                        'month' => request('month'),
                        'year' => request('year'),
                        'classroom_id' => request('classroom_id')
                    ]) }}"

                    class="flex items-center gap-2 px-4 py-2 shadow-sm rounded-xl transition-all duration-200

                    {{ request('tab') == 'monthly'
                        ? 'btn-primary'
                        : 'bg-surface border border-custom text-[var(--text-main)]
                        hover:border-[var(--primary)]
                        hover:bg-[var(--primary-light)]
                        hover:text-[var(--primary)]' }}">

                    {{-- ICON --}}
                    <iconify-icon
                        icon="solar:calendar-bold-duotone"
                        width="20">
                    </iconify-icon>

                    {{-- TEXT --}}
                    <span class="text-sm font-semibold">
                        Bulanan
                    </span>

                </a>

                {{-- TAHUNAN --}}
                <a href="{{ route('attendance.recap', [
                        'tab' => 'yearly',
                        'year' => request('year'),
                        'classroom_id' => request('classroom_id')
                    ]) }}"

                    class="flex items-center gap-2 px-4 py-2 shadow-sm rounded-xl transition-all duration-200

                    {{ request('tab') == 'yearly'
                        ? 'btn-primary'
                        : 'bg-surface border border-custom text-[var(--text-main)]
                        hover:border-[var(--primary)]
                        hover:bg-[var(--primary-light)]
                        hover:text-[var(--primary)]' }}">

                    {{-- ICON --}}
                    <iconify-icon
                        icon="solar:calendar-mark-bold-duotone"
                        width="20">
                    </iconify-icon>

                    {{-- TEXT --}}
                    <span class="text-sm font-semibold">
                        Tahunan
                    </span>

                </a>

            </div>

            {{-- ================= FILTER HARIAN ================= --}}
            @if(request('tab', 'daily') == 'daily')

            <div class="p-5 border shadow-sm bg-surface border-custom rounded-2xl">

                <form method="GET"
                    action="{{ route('attendance.recap') }}"
                    x-data="{ classroomId: '{{ request('classroom_id', $classroomId) }}' }">

                    <input type="hidden" name="tab" value="daily">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        {{-- BULAN --}}
                        <div>
                            <label class="block mb-2 text-sm font-semibold">
                                Bulan
                            </label>

                            <select name="month" class="w-full input-solid rounded-xl">
                                @foreach(range(1,12) as $monthLoop)
                                    <option value="{{ $monthLoop }}"
                                        {{ request('month', now()->month) == $monthLoop ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($monthLoop)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TAHUN --}}
                        <div>
                            <label class="block mb-2 text-sm font-semibold">
                                Tahun
                            </label>

                            <select name="year" class="w-full input-solid rounded-xl">
                                @foreach(range(now()->year, now()->year - 5) as $yearLoop)
                                    <option value="{{ $yearLoop }}"
                                        {{ request('year', now()->year) == $yearLoop ? 'selected' : '' }}>
                                        {{ $yearLoop }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KELAS --}}
                        @role('guru')
                        <div>
                            <label class="block mb-2 text-sm font-semibold">
                                Kelas
                            </label>

                            <select name="classroom_id"
                                x-model="classroomId"
                                class="w-full input-solid rounded-xl">

                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}"
                                        {{ $classroomId == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="classroom_id" value="{{ $classroomId }}">
                        @endrole

                    </div>

                    <div class="flex items-end mt-8">
                        <button type="submit" class="w-full shadow-sm btn-primary">
                            <iconify-icon icon="solar:chart-bold-duotone" width="20"></iconify-icon>
                            Tampilkan
                        </button>
                    </div>

                </form>

            </div>

            @endif

            {{-- ================= FILTER BULANAN ================= --}}
            @if(request('tab') == 'monthly')

                <div class="p-5 border shadow-sm bg-surface border-custom rounded-2xl">

                    <form method="GET"
                        action="{{ route('attendance.recap') }}"
                        x-data="{ classroomId: '{{ request('classroom_id', $classroomId) }}' }">

                        <input type="hidden"
                            name="tab"
                            value="monthly">

                        {{-- ROW --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                            {{-- BULAN --}}
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                                    Bulan
                                </label>

                                <select name="month"
                                        class="input-solid w-full
                                        bg-[var(--surface)]
                                        border-2 border-[var(--border)]
                                        shadow-sm rounded-xl">

                                    @foreach(range(1,12) as $monthLoop)
                                        <option value="{{ $monthLoop }}"
                                            {{ request('month', now()->month) == $monthLoop ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($monthLoop)->translatedFormat('F') }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- TAHUN --}}
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                                    Tahun
                                </label>

                                <select name="year"
                                        class="input-solid w-full
                                        bg-[var(--surface)]
                                        border-2 border-[var(--border)]
                                        shadow-sm rounded-xl">

                                    @foreach(range(now()->year, now()->year - 5) as $yearLoop)
                                        <option value="{{ $yearLoop }}"
                                            {{ request('year', now()->year) == $yearLoop ? 'selected' : '' }}>
                                            {{ $yearLoop }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- KELAS --}}
                            @role('guru')
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                                        Kelas
                                    </label>

                                    <select name="classroom_id"
                                            x-model="classroomId"
                                            required
                                            class="input-solid w-full
                                            bg-[var(--surface)]
                                            border-2 border-[var(--border)]
                                            shadow-sm rounded-xl">

                                        <option value="">
                                            ----------
                                        </option>

                                        @foreach($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">
                                                {{ $classroom->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <p x-show="!classroomId"
                                    class="mt-1 text-sm text-red-500">
                                        Pilih kelas!
                                    </p>
                                </div>
                            @else
                                <input type="hidden"
                                    name="classroom_id"
                                    value="{{ $classroomId }}">
                            @endrole

                            

                        </div>

                        {{-- BUTTON --}}
                        <div class="flex items-end mt-8">
                            <button type="submit"
                                    :disabled="!classroomId"
                                    :class="!classroomId ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="w-full shadow-sm btn-primary">

                                <iconify-icon
                                    icon="solar:chart-bold-duotone"
                                    width="20">
                                </iconify-icon>

                                Tampilkan
                            </button>
                        </div>

                    </form>

                </div>

            @endif

            {{-- ================= FILTER TAHUNAN ================= --}}
            @if(request('tab') == 'yearly')

                <div class="p-5 border shadow-sm bg-surface border-custom rounded-2xl">

                    <form method="GET"
                        action="{{ route('attendance.recap') }}"
                        x-data="{ classroomId: '{{ request('classroom_id', $classroomId) }}' }">

                        <input type="hidden"
                            name="tab"
                            value="yearly">

                        {{-- ROW 1 --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            {{-- TAHUN --}}
                            <div>

                                <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                                    Tahun
                                </label>

                                <select name="year"
                                        class="input-solid w-full
                                        bg-[var(--surface)]
                                        border-2 border-[var(--border)]
                                        shadow-sm rounded-xl">

                                    @foreach(range(now()->year, now()->year - 5) as $yearLoop)

                                        <option value="{{ $yearLoop }}"
                                            {{ request('year', now()->year) == $yearLoop ? 'selected' : '' }}>

                                            {{ $yearLoop }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- ROW 2 --}}
                            @role('guru')

                                <div>

                                    <label class="block mb-2 text-sm font-semibold text-[var(--text-main)]">
                                        Kelas
                                    </label>

                                    <select name="classroom_id"
                                            x-model="classroomId"
                                            required
                                            class="input-solid w-full
                                            bg-[var(--surface)]
                                            border-2 border-[var(--border)]
                                            shadow-sm rounded-xl">

                                        <option value="">
                                            ----------
                                        </option>

                                        @foreach($classrooms as $classroom)

                                            <option value="{{ $classroom->id }}"
                                                {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>

                                                {{ $classroom->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <p x-show="!classroomId"
                                    class="mt-1 text-sm text-red-500">

                                        Pilih kelas!

                                    </p>

                                </div>

                            @else

                                <input type="hidden"
                                    name="classroom_id"
                                    value="{{ $classroomId }}">

                            @endrole

                            

                        </div>

                        {{-- BUTTON --}}
                        <div class="flex items-end mt-8">

                            <button type="submit"
                                    :disabled="!classroomId"
                                    :class="!classroomId
                                        ? 'opacity-50 cursor-not-allowed'
                                        : ''"
                                    class="w-full shadow-sm btn-primary">

                                <iconify-icon
                                    icon="solar:chart-bold-duotone"
                                    width="20">
                                </iconify-icon>

                                Tampilkan

                            </button>

                        </div>

                    </form>

                </div>

            @endif

            {{-- ================= TABLE REKAP HARIAN BULANAN ================= --}}
            @if(request('tab', 'daily') == 'daily')

            <div class="overflow-hidden bg-white shadow-sm rounded-xl">

                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead class="bg-gray-50">

                            <tr class="text-gray-600">

                                <th class="sticky left-0 z-20 p-3 text-left bg-gray-50 min-w-48">
                                    Nama Siswa
                                </th>

                                @foreach($daysInMonth as $day)
                                    <th class="p-3 text-center min-w-12">
                                        {{ $day }}
                                    </th>
                                @endforeach

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($dailyMatrix as $row)

                                <tr class="border-t">

                                    <td class="sticky left-0 z-10 p-3 font-semibold bg-white min-w-48">
                                        {{ $row['student']->name }}
                                    </td>

                                    @foreach($daysInMonth as $day)

                                        @php
                                            $detail = $row['attendances'][$day] ?? null;

                                            $statusLabel = [
                                                'hadir' => '✓',
                                                'sakit' => 'S',
                                                'izin' => 'I',
                                                'alpha' => 'A',
                                            ];

                                            $statusColor = [
                                                'hadir' => 'text-green-600 bg-green-50',
                                                'sakit' => 'text-blue-600 bg-blue-50',
                                                'izin' => 'text-yellow-600 bg-yellow-50',
                                                'alpha' => 'text-red-600 bg-red-50',
                                            ];
                                        @endphp

                                        <td class="p-2 text-center border-l">

                                            @if($detail)

                                                @role('guru')
                                                    <button type="button"
                                                        onclick="openEditModal(
                                                            {{ $detail->id }},
                                                            '{{ $detail->status }}',
                                                            @js($detail->note ?? ''),
                                                            '{{ $detail->attendance->session }}'
                                                        )"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold rounded-lg
                                                        {{ $statusColor[$detail->status] ?? 'bg-gray-50 text-gray-500' }}">

                                                        {{ $statusLabel[$detail->status] ?? '-' }}

                                                    </button>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold rounded-lg
                                                        {{ $statusColor[$detail->status] ?? 'bg-gray-50 text-gray-500' }}">

                                                        {{ $statusLabel[$detail->status] ?? '-' }}

                                                    </span>
                                                @endrole

                                            @else

                                                <span class="text-gray-300">-</span>

                                            @endif

                                        </td>

                                    @endforeach

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="{{ $daysInMonth->count() + 1 }}"
                                        class="p-10 text-center text-gray-500">
                                        Belum ada data absensi harian
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @endif
            
            {{-- ================= TABLE BULANAN ================= --}}
            @if(request('tab') == 'monthly')

                <div class="overflow-hidden bg-white shadow-sm rounded-xl">

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            {{-- HEADER --}}
                            <thead class="bg-gray-50">

                                <tr class="text-sm text-gray-600">

                                    <th class="p-3 text-left">
                                        Nama
                                    </th>

                                    <th class="p-3 text-center">
                                        Hadir
                                    </th>

                                    <th class="p-3 text-center">
                                        Izin
                                    </th>

                                    <th class="p-3 text-center">
                                        Sakit
                                    </th>

                                    <th class="p-3 text-center">
                                        Alpha
                                    </th>

                                    <th class="p-3 text-center">
                                        Persentase
                                    </th>

                                </tr>

                            </thead>

                            {{-- BODY --}}
                            <tbody>
                                @if($monthlyData->count())
                                    @foreach($monthlyData as $item)

                                    <tr class="border-t">

                                        <td class="p-3">
                                            {{ $item['student']->name }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-green-600">
                                            {{ $item['hadir'] }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-yellow-600">
                                            {{ $item['izin'] }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-blue-600">
                                            {{ $item['sakit'] }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-red-600">
                                            {{ $item['alpha'] }}
                                        </td>

                                        <td class="p-3 text-center">

                                            <span class="px-2 py-1 text-xs font-semibold text-white rounded bg-[var(--primary)]">

                                                {{ $item['persentase'] }}%

                                            </span>

                                        </td>

                                    </tr>

                                    @endforeach
                                @else
                                    <tr>

                                        <td colspan="6"
                                            class="p-5 text-center text-gray-500">

                                            Belum ada data absensi bulanan!

                                        </td>

                                    </tr>
                                    @endif
                            </tbody>

                        </table>

                    </div>

                </div>
            @endif  

            {{-- ================= TABLE TAHUNAN ================= --}}
            @if(request('tab') == 'yearly')

                <div class="overflow-hidden bg-white shadow-sm rounded-xl">

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            {{-- HEADER --}}
                            <thead class="bg-gray-50">

                                <tr class="text-sm text-gray-600">

                                    <th class="p-3 text-left">
                                        Nama
                                    </th>

                                    <th class="p-3 text-center">
                                        Hadir
                                    </th>

                                    <th class="p-3 text-center">
                                        Izin
                                    </th>

                                    <th class="p-3 text-center">
                                        Sakit
                                    </th>

                                    <th class="p-3 text-center">
                                        Alpha
                                    </th>

                                    <th class="p-3 text-center">
                                        Persentase
                                    </th>

                                </tr>

                            </thead>

                            {{-- BODY --}}
                            <tbody>

                                @if($yearlyData->count())

                                    @foreach($yearlyData as $item)

                                    <tr class="border-t">

                                        <td class="p-3">
                                            {{ $item['student']->name }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-green-600">
                                            {{ $item['hadir'] }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-yellow-600">
                                            {{ $item['izin'] }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-blue-600">
                                            {{ $item['sakit'] }}
                                        </td>

                                        <td class="p-3 font-semibold text-center text-red-600">
                                            {{ $item['alpha'] }}
                                        </td>

                                        <td class="p-3 text-center">

                                            <span class="px-2 py-1 text-xs font-semibold text-white rounded bg-[var(--primary)]">

                                                {{ $item['persentase'] }}%

                                            </span>

                                        </td>

                                    </tr>

                                    @endforeach

                                @else

                                    <tr>

                                        <td colspan="6"
                                            class="p-10 text-center text-gray-500">

                                            Belum ada data absensi tahunan

                                        </td>

                                    </tr>

                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

        </div>

    </div>

    @role('guru')
    <div id="editAttendanceModal"
        onclick="closeEditModalOutside(event)"
        class="fixed inset-0 z-50 items-center justify-center hidden bg-black/60">

        <div class="w-full max-w-4xl p-6 mx-4 bg-white shadow-xl rounded-2xl">

            <div class="flex items-center justify-between mb-6">

                <h3 class="text-lg font-bold">
                    Edit Absensi Siswa
                </h3>

                <button type="button"
                    onclick="closeEditModal()"
                    class="flex items-center justify-center text-white bg-red-500 rounded-lg w-9 h-9 hover:bg-red-600">
                    ✕
                </button>

            </div>

            <form id="editAttendanceForm" method="POST">

                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                    {{-- STATUS --}}
                    <div>
                        <label class="block mb-1 text-sm font-semibold">
                            Status
                        </label>

                        <select name="status"
                            id="modalStatus"
                            onchange="toggleModalNote()"
                            class="w-full border-gray-300 rounded-lg">

                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>

                        </select>
                    </div>

                    {{-- NOTE --}}
                    <div>
                        <label class="block mb-1 text-sm font-semibold">
                            Keterangan
                        </label>

                        <input type="text"
                            id="modalNote"
                            name="note"
                            class="w-full border-gray-300 rounded-lg disabled:bg-gray-100 disabled:text-gray-400"
                            placeholder="Keterangan...">
                    </div>

                    {{-- SESI --}}
                    <div>
                        <label class="block mb-1 text-sm font-semibold">
                            Sesi
                        </label>

                        <select name="session"
                            id="modalSession"
                            class="w-full border-gray-300 rounded-lg">

                            <option value="pagi">Pagi</option>
                            <option value="sore">Sore</option>

                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex items-end">
                        <button class="w-full btn-primary">
                            Simpan
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>
    @endrole

</x-app-layout>
    <script>
        function openEditModal(id, status, note, session)
        {
            const modal = document.getElementById('editAttendanceModal');
            const form = document.getElementById('editAttendanceForm');

            form.action = `/attendance-recaps/update/${id}`;

            document.getElementById('modalStatus').value = status;
            document.getElementById('modalNote').value = status === 'hadir' ? '' : note;
            document.getElementById('modalSession').value = session;

            toggleModalNote();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal()
        {
            const modal = document.getElementById('editAttendanceModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function closeEditModalOutside(event)
        {
            if (event.target.id === 'editAttendanceModal') {
                closeEditModal();
            }
        }

        function toggleModalNote()
        {
            const status = document.getElementById('modalStatus');
            const note = document.getElementById('modalNote');

            if (status.value === 'hadir') {
                note.value = '';
                note.disabled = true;
            } else {
                note.disabled = false;
            }
        }
    </script>