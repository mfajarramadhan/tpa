<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Orang Tua</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

            <div class="grid gap-6 md:grid-cols-2">
                @foreach($students as $student)
                    <div class="p-5 bg-white border-l-4 shadow-sm rounded-2xl
                        {{ $student->status == 'aktif'
                            ? 'border-[var(--success)]'
                            : ($student->status == 'ditolak'
                                ? 'border-[var(--danger)]'
                                : 'border-yellow-500') }}">

                        {{-- HEADER --}}
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex items-start gap-3">

                                {{-- ICON --}}
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-xl
                                    {{ $student->status == 'aktif'
                                        ? 'bg-[var(--success-light)] text-[var(--success)]'
                                        : ($student->status == 'ditolak'
                                            ? 'bg-[var(--danger-light)] text-[var(--danger)]'
                                            : 'bg-yellow-100 text-yellow-600') }}">

                                    <iconify-icon
                                        icon="solar:user-bold-duotone"
                                        width="24">
                                    </iconify-icon>

                                </div>

                                {{-- INFO --}}
                                <div>

                                    <h4 class="text-lg font-bold break-words text-slate-800">
                                        {{ $student->name }}
                                    </h4>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $student->classroom->name ?? 'Belum terdaftar kelas' }}
                                    </p>

                                </div>

                            </div>

                            {{-- STATUS --}}
                            <div>

                                @if($student->status == 'nonaktif')

                                    <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                        Menunggu
                                    </span>

                                @elseif($student->status == 'aktif')

                                    <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                        Aktif
                                    </span>

                                @elseif($student->status == 'ditolak')

                                    <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                        Ditolak
                                    </span>

                                @endif

                            </div>

                        </div>

                        {{-- REJECT REASON --}}
                        @if($student->status == 'ditolak')

                            <div class="p-3 mt-4 border border-red-200 rounded-xl bg-red-50">

                                <p class="mb-1 text-xs font-semibold tracking-wide text-red-500 uppercase">
                                    Alasan Penolakan
                                </p>

                                <p class="text-sm text-red-600 break-words">
                                    {{ $student->reject_reason }}
                                </p>

                            </div>

                        @endif

                        {{-- DIVIDER --}}
                        <div class="my-2 border-t border-slate-100"></div>

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
                                        <p class="font-semibold text-md text-slate-700">
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
                                        <p class="font-semibold text-md text-slate-700">
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
                                    class="block p-4 transition border rounded-xl bg-slate-50 border-slate-100 hover:border-[var(--primary)] hover:bg-[var(--primary-light)] hover:shadow-sm">

                                @else
                                <a href="{{ route('payments.index', ['student_id' => $student->id]) }}"
                                    class="block p-4 transition border rounded-xl bg-slate-50 border-slate-100 hover:border-[var(--primary)] hover:bg-[var(--primary-light)] hover:shadow-sm">
                                @endif

                                    
                                    @if($monthlyPayments->isEmpty())

                                        <p class="text-sm font-semibold text-slate-400">
                                            Belum ada data iuran
                                        </p>

                                    @else

                                        <p class="text-sm font-semibold text-slate-400">
                                            Sisa Tagihan
                                        </p>

                                        <p class="mt-1 text-xl font-bold text-[var(--danger)]">
                                            Rp {{ number_format($sisaTagihan) }}
                                        </p>

                                        @if($sisaTagihan == 0)

                                            <div class="flex items-center gap-2 mt-3 text-sm text-green-600">

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