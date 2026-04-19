<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            History Iuran - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto">

        {{-- ALERT --}}
            @if(session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif

        {{-- TOTAL --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-4 text-red-700 bg-red-100 rounded">
                <strong>Total Tunggakan</strong><br>
                Rp {{ number_format($totalUnpaid) }}
            </div>

            <div class="p-4 text-green-700 bg-green-100 rounded">
                <strong>Total Dibayar</strong><br>
                Rp {{ number_format($totalPaid) }}
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden bg-white rounded shadow">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Bulan</th>
                        <th class="p-3">Tanggal Pembayaran</th>                        <th class="p-3">Nominal</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Bukti</th>
                        <th class="p-3 text-center">Aksi</th>
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

                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3">
                                @if($payment->month)
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->format('F Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3">
                                @if($payment->paid_at)
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3">
                                Rp {{ number_format($payment->amount) }}
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs text-white
                                    @if($payment->status == 'paid') bg-green-500
                                    @elseif($payment->status == 'rejected') bg-red-600
                                    @elseif($payment->proof_file) bg-yellow-500
                                    @elseif($isLate) bg-red-600
                                    @else bg-gray-500
                                    @endif
                                ">

                                    @if($payment->status == 'paid')
                                        Lunas

                                    @elseif($payment->status == 'rejected')
                                        Ditolak

                                    @elseif($payment->proof_file)
                                        Menunggu Konfirmasi

                                    @elseif($isLate)
                                        Telat

                                    @else
                                        Belum Bayar
                                    @endif

                                </span>
                            </td>

                            <td class="p-3">
                                @if($payment->proof_file)
                                    <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                        class="object-cover w-16 h-16 border rounded cursor-pointer"
                                        onclick="window.open(this.src)">
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td class="p-3 text-center">
                                @if($payment->proof_file && $payment->status == 'pending')

                                    <div x-data="{ showReject: false }" class="flex flex-col items-center gap-2">

                                        <div x-data="{ open: false }">

                                        <div class="flex justify-center gap-2">

                                            {{-- APPROVE --}}
                                            <form method="POST" action="{{ route('payments.approve', $payment->id) }}">
                                                @csrf
                                                <button class="px-3 py-1 text-xs text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                    ✔ Approve
                                                </button>
                                            </form>

                                            {{-- OPEN MODAL --}}
                                            <button @click="open = true"
                                                    class="px-3 py-1 text-xs text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                ✖ Tolak
                                            </button>
                                        </div>

                                        {{-- MODAL --}}
                                        <div x-show="open"
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">

                                            <div @click.outside="open = false"
                                                class="w-full max-w-md p-4 bg-white rounded-xl">

                                                <h2 class="mb-2 text-sm font-semibold">Alasan Penolakan</h2>

                                                <form method="POST" action="{{ route('payments.reject', $payment->id) }}">
                                                    @csrf

                                                    <textarea name="reject_reason"
                                                            rows="3"
                                                            class="w-full p-2 text-sm border rounded-lg focus:ring focus:ring-red-200"
                                                            placeholder="Tulis alasan..."
                                                            required></textarea>

                                                    <div class="flex justify-end gap-2 mt-3">
                                                        <button type="button"
                                                                @click="open = false"
                                                                class="px-3 py-1 text-sm bg-gray-200 rounded-lg">
                                                            Batal
                                                        </button>

                                                        <button class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                            Kirim
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>

                                    </div>
                                @elseif($payment->status == 'rejected')

                                    <span class="text-xs text-red-500">
                                        {{ $payment->reject_reason }}
                                    </span>

                                @else
                                    -
                                @endif
                            </td>

                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>