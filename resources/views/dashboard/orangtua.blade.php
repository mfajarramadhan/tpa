<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Orang Tua</h2>
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

            <!-- Welcome Section -->
            <div class="flex flex-col items-start justify-between gap-4 mb-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="flex items-center gap-2 m-0">Selamat Datang, {{ Auth::user()->name }} <iconify-icon icon="solar:hand-stars-bold-duotone" class="text-[#FFC107]"></iconify-icon></h1>
                    <p class="text-body mt-1 font-medium text-[var(--text-tertiary)]">Berikut ringkasan informasi anak secara real-time!</p>
                </div>
                {{-- <button class="shadow-sm btn-primary">
                    <iconify-icon icon="solar:printer-bold-duotone" width="20"></iconify-icon> Cetak Ringkasan
                </button> --}}
            </div>

            {{-- Card --}}
            <div class="grid gap-6 md:grid-cols-2">
            @foreach($students as $student)

                <div class="px-5 py-3 bg-surface border border-custom border-l-4 shadow-sm rounded-2xl
                    {{ $student->status == 'aktif'
                        ? '!border-l-[var(--success)]'
                        : ($student->status == 'ditolak'
                            ? '!border-l-[var(--danger)]'
                            : '!border-l-yellow-500') }}">

                    {{-- HEADER --}}
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex items-start gap-3">

                            {{-- ICON --}}
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-xl
                                {{ $student->status == 'aktif'
                                    ? 'bg-[var(--success-light)] text-[var(--success)]'
                                    : ($student->status == 'ditolak'
                                        ? 'bg-[var(--danger-light)] text-[var(--danger)]'
                                        : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400') }}">

                                <iconify-icon
                                    icon="solar:user-bold-duotone"
                                    width="24">
                                </iconify-icon>

                            </div>

                            {{-- INFO --}}
                            <div>

                                <h4 class="text-lg font-bold break-words text-[var(--text-main)]">
                                    {{ $student->name }}
                                </h4>

                                <p class="text-sm text-[var(--text-secondary)]">
                                    {{ $student->classroom->name ?? 'Belum terdaftar kelas' }}
                                </p>

                            </div>

                        </div>

                        {{-- STATUS --}}
                        <div>

                            @if($student->status == 'nonaktif')

                                <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-500/15 dark:text-yellow-400">
                                    Menunggu
                                </span>

                            @elseif($student->status == 'aktif')

                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    bg-[var(--success-light)] text-[var(--success)]">
                                    Aktif
                                </span>

                            @elseif($student->status == 'ditolak')

                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    bg-[var(--danger-light)] text-[var(--danger)]">
                                    Ditolak
                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- REJECT REASON --}}
                    @if($student->status == 'ditolak')

                        <div class="p-3 mt-4 border rounded-xl
                            border-[var(--danger-light)]
                            bg-[var(--danger-light)]/30">

                            <p class="mb-1 text-xs font-semibold tracking-wide uppercase text-[var(--danger)]">
                                Alasan Penolakan
                            </p>

                            <p class="text-sm break-words text-[var(--danger)]">
                                {{ $student->reject_reason }}
                            </p>

                        </div>

                    @endif

                    {{-- DIVIDER --}}
                    <div class="my-2 border-t border-custom"></div>

                    {{-- IURAN --}}
                    <div>

                        @if($student->status == 'ditolak')

                            <a href="{{ route('students.reapply', $student->id) }}"
                                class="flex items-center justify-center w-full gap-2 py-3 text-sm font-semibold text-white transition shadow-sm rounded-xl bg-[var(--danger)] hover:opacity-90">

                                <iconify-icon
                                    icon="solar:refresh-bold-duotone"
                                    width="20">
                                </iconify-icon>

                                Perbaiki & Daftar Ulang

                            </a>

                        @else

                            @if($student->status == 'nonaktif')

                                <div class="flex items-center justify-between mb-3">

                                    <div>
                                        <p class="font-semibold text-md text-[var(--text-main)]">
                                            Status Iuran
                                        </p>
                                    </div>

                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[var(--danger-light)] text-[var(--danger)]">

                                        <iconify-icon
                                            icon="solar:wallet-money-bold-duotone"
                                            width="20">
                                        </iconify-icon>

                                    </div>

                                </div>

                            @else

                                <a href="{{ route('payments.index', ['student_id' => $student->id]) }}"
                                    class="flex items-center justify-between mb-3 transition hover:opacity-80">

                                    <div>
                                        <p class="font-semibold text-md text-[var(--text-main)]">
                                            Status Iuran
                                        </p>
                                    </div>

                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-[var(--danger-light)] text-[var(--danger)]">

                                        <iconify-icon
                                            icon="solar:wallet-money-bold-duotone"
                                            width="20">
                                        </iconify-icon>

                                    </div>

                                </a>

                            @endif

                            @php
                                $monthlyPayments = $student->payments->where('type', 'monthly');

                                $totalTagihan = $monthlyPayments->sum('original_amount');

                                $totalDibayar = $monthlyPayments
                                    ->where('status', 'paid')
                                    ->sum('original_amount');

                                $sisaTagihan = $totalTagihan - $totalDibayar;
                            @endphp

                            @if($student->status == 'nonaktif')

                                <a href="#"
                                    class="block p-3 transition border rounded-xl
                                    bg-[var(--bg)]
                                    border-custom
                                    hover:border-[var(--primary)]
                                    hover:bg-[var(--primary-light)]
                                    hover:shadow-sm">

                            @else

                                <a href="{{ route('payments.index', ['student_id' => $student->id]) }}"
                                    class="block p-3 transition border rounded-xl
                                    bg-[var(--bg)]
                                    border-custom
                                    hover:border-[var(--primary)]
                                    hover:bg-[var(--primary-light)]
                                    hover:shadow-sm">

                            @endif

                                @if($monthlyPayments->isEmpty())

                                    <p class="text-sm font-semibold text-[var(--text-tertiary)]">
                                        Belum ada data iuran
                                    </p>

                                @else

                                    <p class="text-sm font-semibold text-[var(--text-tertiary)]">
                                        Sisa Tagihan
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-[var(--danger)]">
                                        Rp {{ number_format($sisaTagihan) }}
                                    </p>

                                    @if($sisaTagihan == 0)

                                        <div class="flex items-center gap-2 mt-2 text-xs text-[var(--success)]">

                                            <iconify-icon
                                                icon="solar:check-circle-bold-duotone"
                                                width="18">
                                            </iconify-icon>

                                            Semua iuran sudah lunas

                                        </div>

                                    @endif

                                @endif

                            </a>

                        @endif

                    </div>

                </div>

            @endforeach
        </div>

        </div>
    </div>
</x-app-layout>