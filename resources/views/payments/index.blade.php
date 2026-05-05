<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Iuran Bulanan</h2>
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

            {{-- TOTAL TUNGGAKAN --}}
            @role('orang_tua')
            <div class="grid grid-cols-3 gap-4 mb-4">

                <div class="p-4 bg-white rounded shadow">
                    <p class="text-sm text-gray-500">Total Tagihan</p>
                    <p class="text-lg font-bold">Rp {{ number_format($totalUnpaid + $totalPaid) }}</p>
                </div>

                <div class="p-4 bg-white rounded shadow">
                    <p class="text-sm text-gray-500">Total Dibayar</p>
                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($totalPaid) }}</p>
                </div>

                <div class="p-4 bg-white rounded shadow">
                    <p class="text-sm text-gray-500">Sisa Tagihan</p>
                    <p class="text-lg font-bold text-red-600">Rp {{ number_format($totalUnpaid) }}</p>
                </div>

            </div>
            @endrole

            @role('superadmin')
            <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">

            {{-- Total Tagihan --}}
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Total Tagihan</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                        <iconify-icon icon="solar:wallet-money-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div class="text-data">
                    Rp {{ number_format($totalTagihanAll) }}
                </div>
            </div>

            {{-- Total Dibayar --}}
            <div class="stat-card" style="border-left-color: var(--success);">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Total Dibayar</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--success-light)] text-[var(--success)]">
                        <iconify-icon icon="solar:check-circle-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div class="text-data text-[var(--success)]">
                    Rp {{ number_format($totalDibayarAll) }}
                </div>
            </div>

            {{-- Sisa Tagihan --}}
            <div class="stat-card" style="border-left-color: var(--danger);">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Sisa Tagihan</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--danger-light)] text-[var(--danger)]">
                        <iconify-icon icon="solar:danger-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div class="text-data text-[var(--danger)]">
                    Rp {{ number_format($sisaTagihanAll) }}
                </div>
            </div>

        </div>
            @endrole

            {{-- LIST ANAK --}}
            @role('orang_tua')
            <div class="flex gap-3 mb-4">

                @foreach($students as $student)
                    <a href="{{ route('payments.index', ['student_id' => $student->id]) }}"
                    class="px-4 py-2 rounded border
                    {{ $selectedStudent && $selectedStudent->id == $student->id ? 'bg-blue-600 text-white' : 'bg-white' }}">

                        {{ $student->name }}
                    </a>
                @endforeach

            </div>
            @endrole

            @role('orang_tua')
            @if($selectedStudent)
                <h3 class="mb-2 font-semibold">
                    Rincian Iuran - {{ $selectedStudent->name }}
                </h3>
            @endif
            @endrole

            @role('orang_tua')
            {{-- Cek ada tagihan atau tidak --}}
            @if($selectedStudent)
                @php
                    $payments = $selectedStudent->payments->where('type', 'monthly');

                    // tagihan normal (belum bayar)
                    $hasUnpaidNormal = $payments
                        ->where('status', 'pending')
                        ->whereNull('proof_file')
                        ->count() > 0;

                    // ada yang ditolak
                    $hasRejected = $payments
                        ->where('status', 'rejected')
                        ->count() > 0;
                @endphp
            @else
                <div class="p-4 text-sm text-gray-500 bg-gray-100 rounded">
                    Belum ada data siswa.
                </div>
            @endif

            {{-- BUTTON BAYAR --}}
            @if($selectedStudent)
                <div class="mb-3">
                    @if($hasUnpaidNormal)
                        <a href="{{ route('payments.create', ['student_id' => $selectedStudent->id]) }}"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            Bayar Iuran
                        </a>

                    @elseif($hasRejected)
                        <button disabled
                            class="px-4 py-2 text-white bg-red-400 rounded cursor-not-allowed">
                            Perbaiki Pembayaran Ditolak
                        </button>

                    @else
                        <button disabled
                            class="px-4 py-2 text-white bg-gray-400 rounded cursor-not-allowed">
                            Tidak Ada Tagihan
                        </button>
                    @endif
                </div>
            @endif
            @endrole
            
            @role('superadmin')

            <h3 class="mb-3 font-semibold">Data Iuran Siswa</h3>

            <div class="overflow-hidden card-panel">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm table-custom">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Orang Tua</th>
                                <th>Asal Sekolah</th>
                                <th class="text-right">Tagihan</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($studentsSummary as $data)
                                @php
                                    $student = $data['student'];
                                    $tagihan = $data['total_tagihan'] - $data['total_dibayar'];
                                    $status = $data['status'];

                                    // ambil inisial
                                    $initial = strtoupper(substr($student->name, 0, 2));

                                    // mapping badge
                                    if ($status == 'Lunas') {
                                        $badge = 'badge-success';
                                        $icon = 'solar:check-circle-bold-duotone';
                                    } elseif ($status == 'Menunggak') {
                                        $badge = 'badge-danger';
                                        $icon = 'solar:close-circle-bold-duotone';
                                    } elseif ($status == 'Menunggu Konfirmasi') {
                                        $badge = 'badge-warning';
                                        $icon = 'solar:clock-circle-bold-duotone';
                                    } elseif ($status == 'Tidak ada tagihan') {
                                        $badge = 'badge-info';
                                        $icon = 'solar:info-circle-bold-duotone';
                                    } else {
                                        $badge = 'badge-purple';
                                        $icon = 'solar:question-circle-bold-duotone';
                                    }
                                @endphp

                                <tr>
                                    <!-- Siswa -->
                                    <td>
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-[var(--primary-light)] text-[var(--primary)] flex items-center justify-center font-bold text-sm border border-[var(--primary-light)]">
                                                {{ $initial }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-[var(--text-main)]">
                                                    {{ $student->name }}
                                                </div>
                                                <div class="text-xs text-[var(--text-tertiary)] mt-0.5">
                                                    {{ $student->classroom->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Orang Tua -->
                                    <td class="font-medium">
                                        {{ $student->parent->name }}
                                    </td>

                                    <!-- Asal Sekolah -->
                                    <td>
                                        {{ $student->school_origin ?? '-' }}
                                    </td>

                                    <!-- Tagihan -->
                                    @php
                                        $tagihanColor = $tagihan > 0 ? 'var(--danger)' : 'var(--success)';
                                    @endphp

                                    <td class="font-semibold text-right" style="color: {{ $tagihanColor }}">
                                        Rp {{ number_format($tagihan) }}
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <span class="badge {{ $badge }}">
                                            <iconify-icon icon="{{ $icon }}"></iconify-icon>
                                            {{ $status }}
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="text-right">
                                        <a href="{{ route('payments.student.show', $student->id) }}"
                                        class="btn-icon border border-[var(--primary)] hover:border-[var(--primary)]"
                                        title="Detail">

                                            <iconify-icon 
                                                icon="solar:eye-bold-duotone" 
                                                width="18"
                                                class="text-[var(--primary)]">
                                            </iconify-icon>

                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endrole

            @role("orang_tua")
            {{-- TABLE --}}
            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Status</th>
                            <th class="text-right">Nominal</th>
                            <th>Bukti</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                        <tr>

                            {{-- BULAN --}}
                            <td class="font-semibold text-[var(--text-main)]">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->format('F Y') }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @php
                                    $isLate = false;

                                    if ($payment->month) {
                                        $deadline = \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->startOfMonth();
                                        $isLate = now()->gt($deadline) && $payment->status != 'paid' && !$payment->proof_file;
                                    }
                                @endphp

                                <span class="badge
                                    @if($payment->status == 'paid') badge-success
                                    @elseif($payment->status == 'rejected') badge-danger
                                    @elseif($payment->proof_file) badge-warning
                                    @elseif($isLate) badge-danger
                                    @else badge-info
                                    @endif
                                ">

                                    @if($payment->status == 'paid')
                                        ✔ Lunas
                                    @elseif($payment->status == 'rejected')
                                        ✖ Ditolak
                                    @elseif($payment->proof_file)
                                        ⏳ Menunggu
                                    @elseif($isLate)
                                        ⚠ Telat
                                    @else
                                        ℹ Belum Bayar
                                    @endif

                                </span>
                            </td>

                            {{-- NOMINAL --}}
                            <td class="text-right font-mono text-[var(--text-main)]">
                                Rp {{ number_format($payment->original_amount) }}                            
                            </td>

                            {{-- BUKTI --}}
                            <td>
                                @if($payment->proof_file)
                                    <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                        class="object-cover w-12 h-12 border rounded-lg cursor-pointer hover:shadow"
                                        onclick="window.open(this.src)">
                                @else
                                    <span class="text-small">-</span>
                                @endif
                            </td>

                            {{-- TANGGAL --}}
                            <td class="text-small">
                                @if($payment->paid_at)
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="flex justify-center gap-2">

                                    @if($payment->status == 'paid')
                                        <span class="text-small">-</span>

                                    @elseif($payment->status == 'rejected')
                                        <a href="{{ route('payments.create', ['student_id' => $payment->student_id]) }}"
                                        class="px-3 py-1 text-xs btn-primary">
                                            Perbaiki & Bayar
                                        </a>

                                    @elseif($payment->proof_file)
                                        <span class="text-small">-</span>

                                    @else
                                        <span class="text-small">-</span>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-small">
                                Belum ada data iuran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr class="border-t border-[var(--border)] bg-[var(--bg)]"">

                            <td colspan="3" class="text-right text-small">
                                Total
                            </td>

                            <td class="text-right">
                                <div class="text-small">Tagihan</div>
                                <div class="font-semibold text-[var(--text-main)]">
                                    Rp {{ number_format($payments->sum('original_amount')) }}
                                </div>
                            </td>

                            <td>
                                <div class="text-small text-[var(--success)]">Dibayar</div>
                                <div class="font-semibold text-[var(--success)]">
                                    Rp {{ number_format($payments->where('status','paid')->sum('original_amount')) }}
                                </div>
                            </td>

                            <td></td>

                        </tr>
                    </tfoot>

                </table>
                @endrole("orang_tua")
            </div>

        </div>
    </div>
</x-app-layout>