<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Iuran Bulanan</h2>
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
            
            @role('orang_tua')
            {{-- Cek ada tagihan atau tidak --}}
            @if($selectedStudent)
                @php
                    $studentPayments = $selectedStudent->payments->where('type', 'monthly');

                    // tagihan normal (belum bayar)
                    $hasUnpaidNormal = $studentPayments
                        ->where('status', 'pending')
                        ->whereNull('proof_file')
                        ->count() > 0;

                    // ada yang ditolak
                    $hasRejected = $studentPayments
                        ->where('status', 'rejected')
                        ->count() > 0;
                @endphp
            @else
                <div class="p-4 text-sm text-gray-500 bg-gray-100 rounded">
                    Belum ada data siswa.
                </div>
            @endif

            {{-- LIST ANAK --}}
            @if($selectedStudent)
                <h3 class="mb-2 font-semibold">
                    Rincian Iuran - {{ $selectedStudent->name }}
                </h3>
            @endif

            <div class="flex gap-3 mb-6">

                @foreach($students as $student)
                    <a href="{{ route('payments.index', ['student_id' => $student->id]) }}"
                        class="flex items-center gap-2 px-4 py-2 shadow-sm rounded-xl transition-all duration-200

                        {{ $selectedStudent && $selectedStudent->id == $student->id
                            ? 'btn-primary'
                            : 'bg-surface border border-custom text-[var(--text-main)]
                            hover:border-[var(--primary)]
                            hover:bg-[var(--primary-light)]
                            hover:text-[var(--primary)]' }}">

                        {{-- ICON --}}
                        <iconify-icon
                            icon="solar:user-bold-duotone"
                            width="20">
                        </iconify-icon>

                        {{-- NAME --}}
                        <span class="text-sm font-semibold">
                            {{ $student->name }}
                        </span>

                    </a>
                @endforeach

            </div>

            {{-- TOTAL TUNGGAKAN --}}
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">

                {{-- Total Tagihan --}}
                <div class="stat-card" style="border-left-color: var(--warning);">
                    <div class="flex items-start justify-between mb-3">

                        <span class="text-caption">
                            Total Tagihan
                        </span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--warning-light)] text-[var(--warning)]">
                            <iconify-icon
                                icon="solar:wallet-money-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>

                    </div>

                    <div class="text-data text-[var(--warning)]">
                        Rp {{ number_format($totalUnpaid + $totalPaid) }}
                    </div>
                </div>

                {{-- Total Dibayar --}}
                <div class="stat-card"
                    style="border-left-color: var(--primary);">

                    <div class="flex items-start justify-between mb-3">

                        <span class="text-caption">
                            Total Dibayar
                        </span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:check-circle-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>

                    </div>

                    <div class="text-data text-[var(--primary)]">
                        Rp {{ number_format($totalPaid) }}
                    </div>

                </div>

                {{-- Sisa Tagihan --}}
                <div class="stat-card"
                    style="border-left-color: var(--danger);">

                    <div class="flex items-start justify-between mb-3">

                        <span class="text-caption">
                            Sisa Tagihan
                        </span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--danger-light)] text-[var(--danger)]">
                            <iconify-icon
                                icon="solar:danger-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>

                    </div>

                    <div class="text-data text-[var(--danger)]">
                        Rp {{ number_format($totalUnpaid) }}
                    </div>

                </div>
            </div>
            @endrole

            @role('superadmin')
            <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">

                {{-- Total Tagihan --}}
                <div class="stat-card" style="border-left-color: var(--warning);">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Tagihan</span>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--warning-light)] text-[var(--warning)]">
                            <iconify-icon icon="solar:wallet-money-bold-duotone" width="18"></iconify-icon>
                        </div>
                    </div>
                    <div class="text-data text-[var(--warning)]">
                        Rp {{ number_format($totalTagihanAll) }}
                    </div>
                </div>

                {{-- Total Dibayar --}}
                <div class="stat-card" style="border-left-color: var(--primary);">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Dibayar</span>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon icon="solar:check-circle-bold-duotone" width="18"></iconify-icon>
                        </div>
                    </div>
                    <div class="text-data text-[var(--primary)]">
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

            {{-- BUTTON BAYAR --}}
            @role('orang_tua')
            @if($selectedStudent)

                <div class="mb-4">

                    @if($hasUnpaidNormal)

                        <a href="{{ route('payments.create', ['student_id' => $selectedStudent->id]) }}"
                            class="flex items-center gap-2 shadow-sm btn-primary w-fit">

                            <iconify-icon
                                icon="solar:wallet-money-bold-duotone"
                                width="20">
                            </iconify-icon>

                            Bayar Iuran

                        </a>

                    @elseif($hasRejected)

                        <button disabled
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-400 shadow-sm cursor-not-allowed rounded-xl">

                            <iconify-icon 
                                icon="solar:danger-triangle-bold-duotone"
                                width="20">
                            </iconify-icon>

                            Perbaiki Pembayaran

                        </button>

                    @else

                        <button disabled
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gray-400 shadow-sm cursor-not-allowed rounded-xl">

                            <iconify-icon
                                icon="solar:document-text-bold-duotone"
                                width="20">
                            </iconify-icon>

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
                                <th class="w-[22%]">Siswa</th>
                                <th class="w-[22%]">Orang Tua</th>
                                <th class="w-[24%]">Asal Sekolah</th>
                                <th class="w-[12%]">Tagihan</th>
                                <th class="w-[14%]">Status</th>
                                <th class="w-[6%] !text-center">Aksi</th>
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
                                    } elseif ($status == 'Tanpa tagihan') {
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
                                    <td class="text-center">
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
                    {{-- Pagination --}}
                    @if($studentsSummary->hasPages())

                        <div class="flex items-center justify-between p-5 border-t border-[var(--border-light)] bg-[var(--surface)]">

                            {{-- Info --}}
                            <div class="text-sm font-medium text-[var(--text-tertiary)]">

                                Menampilkan
                                {{ $studentsSummary->firstItem() ?? 0 }}
                                -
                                {{ $studentsSummary->lastItem() ?? 0 }}

                                dari

                                {{ $studentsSummary->total() }}

                                data

                            </div>

                            {{-- Button --}}
                            <div class="flex gap-2">

                                {{-- Prev --}}
                                @if($studentsSummary->onFirstPage())

                                    <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                        &laquo; Prev
                                    </span>

                                @else

                                    <a href="{{ $studentsSummary->previousPageUrl() }}"
                                    class="px-3 py-1.5 text-sm border-transparent btn-outline hover:bg-[var(--border-light)]">

                                        &laquo; Prev

                                    </a>

                                @endif

                                {{-- Number --}}
                                @foreach($studentsSummary->getUrlRange(1, $studentsSummary->lastPage()) as $page => $url)

                                    @if($page == $studentsSummary->currentPage())

                                        <span class="px-3.5 py-1.5 text-sm shadow-md btn-primary">
                                            {{ $page }}
                                        </span>

                                    @else

                                        <a href="{{ $url }}"
                                        class="px-3.5 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                            {{ $page }}

                                        </a>

                                    @endif

                                @endforeach

                                {{-- Next --}}
                                @if($studentsSummary->hasMorePages())

                                    <a href="{{ $studentsSummary->nextPageUrl() }}"
                                    class="px-3 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                        Next &raquo;

                                    </a>

                                @else

                                    <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                        Next &raquo;
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif
                </div>
            </div>

            @endrole

            @role("orang_tua")
            {{-- TABLE --}}
            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th class="w-[18%]">Bulan</th>

                            <th class="w-[16%]">Status</th>

                            <th class="w-[16%] !text-center">Nominal</th>

                            <th class="w-[14%]">Bukti</th>

                            <th class="w-[22%]">Tanggal Bayar</th>

                            <th class="w-[14%] !text-center">Aksi</th>
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

                                    @php
                                        $proofUrl = asset('storage/' . $payment->proof_file);
                                        $ext = strtolower(pathinfo($payment->proof_file, PATHINFO_EXTENSION));
                                    @endphp

                                    {{-- IMAGE --}}
                                    @if(in_array($ext, ['jpg','jpeg','png']))

                                        <img src="{{ $proofUrl }}"
                                            class="object-cover w-12 h-12 transition border rounded-lg cursor-pointer hover:shadow hover:scale-105"
                                            onclick="openPaymentPreview('image', '{{ $proofUrl }}')">

                                    {{-- PDF --}}
                                    @elseif($ext === 'pdf')

                                        <div
                                            onclick="openPaymentPreview('pdf', '{{ $proofUrl }}')"
                                            class="flex items-center justify-center w-12 h-12 transition bg-red-100 border rounded-lg cursor-pointer hover:shadow hover:scale-105">

                                            <span class="text-[10px] font-bold text-red-600">
                                                PDF
                                            </span>

                                        </div>

                                    @endif

                                @else

                                    <span class="text-small">-</span>

                                @endif

                            </td>

                            {{-- TANGGAL --}}
                            <td class="text-small">
                                @if($payment->paid_at)
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y | H:i') }} WIB
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
                                            class="flex items-center gap-1 px-3 py-1 text-xs shadow-sm btn-primary w-fit">

                                            <iconify-icon
                                                icon="solar:pen-bold-duotone"
                                                width="16">
                                            </iconify-icon>

                                            Perbaiki

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
                        <tr class="border-t border-[var(--border)] bg-[var(--bg)]">

                            <td colspan="3" class="text-right text-small">
                                Total
                            </td>

                            <td class="text-right">
                                <div class="text-small">Tagihan</div>
                                <div class="font-semibold text-[var(--text-main)]">
                                    Rp {{ number_format(
                                        $payments->getCollection()
                                            ->sum('original_amount')
                                    ) }}
                                </div>
                            </td>

                            <td>
                                <div class="text-small text-[var(--success)]">Dibayar</div>
                                <div class="font-semibold text-[var(--success)]">
                                    Rp {{ number_format(
                                        $payments->getCollection()
                                            ->where('status', 'paid')
                                            ->sum('original_amount')
                                    ) }}
                                </div>
                            </td>

                            <td></td>

                        </tr>
                    </tfoot>

                </table>
                {{-- Pagination --}}
                @if($payments->hasPages())

                    <div class="flex items-center justify-between p-5 border-t border-[var(--border-light)] bg-[var(--surface)]">

                        {{-- Info --}}
                        <div class="text-sm font-medium text-[var(--text-tertiary)]">

                            Menampilkan
                            {{ $payments->firstItem() ?? 0 }}
                            -
                            {{ $payments->lastItem() ?? 0 }}

                            dari

                            {{ $payments->total() }}

                            data

                        </div>

                        {{-- Button --}}
                        <div class="flex gap-2">

                            {{-- Prev --}}
                            @if($payments->onFirstPage())

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    &laquo; Prev
                                </span>

                            @else

                                <a href="{{ $payments->previousPageUrl() }}"
                                class="px-3 py-1.5 text-sm border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    &laquo; Prev

                                </a>

                            @endif

                            {{-- Number --}}
                            @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)

                                @if($page == $payments->currentPage())

                                    <span class="px-3.5 py-1.5 text-sm shadow-md btn-primary">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $url }}"
                                    class="px-3.5 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                        {{ $page }}

                                    </a>

                                @endif

                            @endforeach

                            {{-- Next --}}
                            @if($payments->hasMorePages())

                                <a href="{{ $payments->nextPageUrl() }}"
                                class="px-3 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    Next &raquo;

                                </a>

                            @else

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    Next &raquo;
                                </span>

                            @endif

                        </div>

                    </div>

                @endif
                @endrole("orang_tua")
            </div>

        </div>
    </div>
    {{-- ================= PAYMENT PREVIEW MODAL ================= --}}
    <div id="paymentPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closePaymentPreview()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="paymentPreviewImage"
                class="hidden object-contain w-full max-h-[90vh] rounded-lg shadow-2xl">

            {{-- PDF --}}
            <iframe id="paymentPreviewPdf"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>
</x-app-layout>
<script>

    function openPaymentPreview(type, src) {

        const modal = document.getElementById('paymentPreviewModal');

        const image = document.getElementById('paymentPreviewImage');
        const pdf = document.getElementById('paymentPreviewPdf');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (type === 'image') {

            image.src = src;
            image.classList.remove('hidden');

        }

        // PDF
        if (type === 'pdf') {

            pdf.src = src;
            pdf.classList.remove('hidden');

        }

        modal.style.display = 'flex';
    }

    function closePaymentPreview() {

        const modal = document.getElementById('paymentPreviewModal');

        document.getElementById('paymentPreviewImage').src = '';
        document.getElementById('paymentPreviewPdf').src = '';

        modal.style.display = 'none';
    }

    // klik backdrop
    document.getElementById('paymentPreviewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'paymentPreviewModal') {
                closePaymentPreview();
            }

        });

</script>