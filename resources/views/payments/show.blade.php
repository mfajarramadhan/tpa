<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            History Iuran - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto">

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
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Nominal</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($payments as $payment)

                        @php
                            $deadline = \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->startOfMonth();
                            $isLate = now()->gt($deadline) && $payment->status != 'paid';
                        @endphp

                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3">{{ $payment->month }}</td>

                            <td class="p-3">
                                {{ $payment->created_at->format('d-m-Y') }}
                            </td>

                            <td class="p-3">
                                Rp {{ number_format($payment->amount) }}
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 text-white rounded text-xs
                                    @if($payment->status == 'paid') bg-green-500
                                    @elseif($isLate) bg-red-600
                                    @else bg-yellow-500
                                    @endif">

                                    @if($payment->status == 'paid')
                                        Lunas
                                    @elseif($isLate)
                                        Telat
                                    @else
                                        Pending
                                    @endif

                                </span>
                            </td>

                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>