<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            History Iuran - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto">

        {{-- ALERT --}}
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

        {{-- TOTAL --}}
        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">

        {{-- Total Tunggakan --}}
        <div class="stat-card" style="border-left-color: var(--danger);">
            <div class="flex items-start justify-between mb-3">
                <span class="text-caption">Total Tunggakan</span>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--danger-light)] text-[var(--danger)]">
                    <iconify-icon icon="solar:danger-bold-duotone" width="18" class="text-[var(--danger)]"></iconify-icon>
                </div>
            </div>
            <div class="text-data text-[var(--danger)]">
                Rp {{ number_format($totalUnpaid) }}
            </div>
        </div>

        {{-- Total Dibayar --}}
        <div class="stat-card" style="border-left-color: var(--success);">
            <div class="flex items-start justify-between mb-3">
                <span class="text-caption">Total Dibayar</span>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--success-light)] text-[var(--success)]">
                    <iconify-icon icon="solar:check-circle-bold-duotone" width="18" class="text-[var(--success)]"></iconify-icon>
                </div>
            </div>
            <div class="text-data text-[var(--success)]">
                Rp {{ number_format($totalPaid) }}
            </div>
        </div>

    </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto card-panel">
            <table class="w-full text-sm table-custom">

                <thead>
                    <tr>
                        <th class="w-[16%]">Bulan</th>
                        <th class="w-[18%]">Tanggal Pembayaran</th>
                        <th class="w-[18%] text-right">Nominal</th>
                        <th class="w-[12%]">Status</th>
                        <th class="w-[12%]">Bukti</th>
                        <th class="w-[8%] !text-center">Aksi</th>
                        <th class="w-[16%]">Catatan</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($payments as $payment)

                    @php
                        $isLate = false;

                        if ($payment->month) {
                            $deadline = \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->startOfMonth();

                            $isLate = now()->gt($deadline)
                                && $payment->status != 'paid'
                                && !$payment->proof_file;
                        }
                    @endphp

                    <tr>

                        {{-- Bulan --}}
                        <td>
                            @if($payment->month)
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->format('F Y') }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- Tanggal --}}
                        <td>
                            @if($payment->paid_at)
                                {{ \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y') }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- Nominal --}}
                        <td class="font-semibold text-[var(--danger)]">
                            Rp {{ number_format($payment->amount) }}
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($payment->status == 'paid')
                                <span class="badge badge-success">
                                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                    Lunas
                                </span>

                            @elseif($payment->status == 'rejected')
                                <span class="badge badge-danger">
                                    <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon>
                                    Ditolak
                                </span>

                            @elseif($payment->proof_file)
                                <span class="badge badge-warning">
                                    <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                    Menunggu
                                </span>

                            @elseif($isLate)
                                <span class="badge badge-danger">
                                    <iconify-icon icon="solar:danger-bold-duotone"></iconify-icon>
                                    Telat
                                </span>

                            @else
                                <span class="badge badge-info">
                                    <iconify-icon icon="solar:info-circle-bold-duotone"></iconify-icon>
                                    Belum Bayar
                                </span>
                            @endif
                        </td>

                        {{-- Bukti --}}
                        <td>

                            @if($payment->proof_file)

                                @php
                                    $proofUrl = asset('storage/' . $payment->proof_file);
                                    $ext = strtolower(pathinfo($payment->proof_file, PATHINFO_EXTENSION));
                                @endphp

                                {{-- IMAGE --}}
                                @if(in_array($ext, ['jpg','jpeg','png']))

                                    <img src="{{ $proofUrl }}"
                                        class="object-cover w-16 h-16 transition border rounded-lg cursor-pointer border-[var(--border)] hover:scale-105"
                                        onclick="openPaymentPreview('image', '{{ $proofUrl }}')">

                                {{-- PDF --}}
                                @elseif($ext === 'pdf')

                                    <div
                                        onclick="openPaymentPreview('pdf', '{{ $proofUrl }}')"
                                        class="flex items-center justify-center w-16 h-16 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 border-[var(--border)]">

                                        <span class="text-xs font-bold text-red-600">
                                            PDF
                                        </span>

                                    </div>

                                @endif

                            @else

                                <span class="text-small">-</span>

                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">

                            @if($payment->proof_file && $payment->status == 'pending')

                                <div x-data="{ open: false }">

                                    <div class="flex justify-center gap-2">

                                        {{-- APPROVE --}}
                                        <form method="POST"
                                            action="{{ route('payments.approve', $payment->id) }}"
                                            onsubmit="return confirm('Yakin ingin menyetujui pembayaran ini?')">

                                            @csrf

                                            <button class="btn-icon group bg-[var(--success-light)] border border-[var(--success)] hover:bg-[var(--success)]"
                                                    title="Approve">

                                                <iconify-icon icon="lets-icons:check-fill"
                                                            width="18"
                                                            class="text-[var(--success)] group-hover:text-white transition">
                                                </iconify-icon>

                                            </button>

                                        </form>

                                        {{-- REJECT --}}
                                        <button @click="open = true"
                                            class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]"
                                            title="Tolak">

                                            <iconify-icon icon="heroicons:x-circle"
                                                        width="18"
                                                        class="text-[var(--danger)] group-hover:text-white transition">
                                            </iconify-icon>

                                        </button>

                                    </div>

                                    {{-- MODAL --}}
                                    <div x-show="open"
                                        x-cloak
                                        x-transition
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

                                        <div @click.outside="open = false"
                                            class="w-full max-w-md p-5 shadow-md bg-surface rounded-xl">

                                            <h3 class="mb-3">
                                                Alasan Penolakan
                                            </h3>

                                            <form method="POST"
                                                action="{{ route('payments.reject', $payment->id) }}">

                                                @csrf

                                                <textarea name="reject_reason"
                                                        rows="3"
                                                        class="input-solid"
                                                        placeholder="Tulis alasan..."
                                                        required></textarea>

                                                <div class="flex justify-end gap-2 mt-4">

                                                    <button type="button"
                                                            @click="open = false"
                                                            class="btn-outline">
                                                        Batal
                                                    </button>

                                                    <button class="btn-primary bg-[var(--danger)] hover:bg-red-700">
                                                        Kirim
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @else

                                <span class="text-small">
                                    -
                                </span>

                            @endif

                        </td>

                        {{-- CATATAN --}}
                        <td class="max-w-[250px]">

                            @if($payment->reject_reason)

                                <p class="text-sm text-[var(--danger)] line-clamp-3 cursor-help"
                                title="{{ $payment->reject_reason }}">

                                    {{ $payment->reject_reason }}

                                </p>

                            @else

                                <span class="text-small">
                                    -
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach
                </tbody>

            </table>
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