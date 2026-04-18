<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Iuran Bulanan</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif

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
            <div class="grid grid-cols-3 gap-4 mb-4">

                <div class="p-4 bg-white rounded shadow">
                    <p class="text-sm text-gray-500">Total Tagihan</p>
                    <p class="text-lg font-bold">
                        Rp {{ number_format($totalTagihanAll) }}
                    </p>
                </div>

                <div class="p-4 bg-white rounded shadow">
                    <p class="text-sm text-gray-500">Total Dibayar</p>
                    <p class="text-lg font-bold text-green-600">
                        Rp {{ number_format($totalDibayarAll) }}
                    </p>
                </div>

                <div class="p-4 bg-white rounded shadow">
                    <p class="text-sm text-gray-500">Sisa Tagihan</p>
                    <p class="text-lg font-bold text-red-600">
                        Rp {{ number_format($sisaTagihanAll) }}
                    </p>
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

            {{-- BUTTON BAYAR --}}
            @role('orang_tua')
            @if($selectedStudent)
                <div class="mb-3">
                    <a href="{{ route('payments.create', ['student_id' => $selectedStudent->id]) }}"
                    class="px-4 py-2 text-white bg-blue-600 rounded">
                        Bayar Iuran
                    </a>
                </div>
            @endif
            @endrole
            
            @role('superadmin')

            <h3 class="mb-3 font-semibold">Data Iuran Siswa</h3>

            <div class="overflow-hidden bg-white rounded shadow">
            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Orang Tua</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3 text-right">Sisa Tagihan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($studentsSummary as $data)
                    <tr class="border-t">

                        <td class="p-3">{{ $data['student']->name }}</td>
                        <td class="p-3">{{ $data['student']->parent->name }}</td>
                        <td class="p-3">{{ $data['student']->classroom->name ?? '-' }}</td>

                        <td class="p-3 font-semibold text-right text-red-600">
                            Rp {{ number_format($data['total_tagihan'] - $data['total_dibayar']) }}
                        </td>

                        <td class="p-3">
                            {{ $data['status'] }}
                        </td>

                        <td class="p-3 text-center">
                            <a href="{{ route('payments.student.show', $data['student']->id) }}"
                            class="text-blue-600 underline">
                                Detail
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>
            </div>

            @endrole

            @role("orang_tua")
            {{-- TABLE --}}
            <div class="overflow-hidden bg-white rounded shadow">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="text-left">
                            <th class="p-3">Bulan</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Nominal</th>
                            <th class="p-3">Bukti</th>
                            <th class="p-3">Tanggal Bayar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                        <tr class="border-t hover:bg-gray-50">

                            {{-- BULAN --}}
                            <td class="p-3">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->format('F Y') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="p-3">
                                @php
                                    $isLate = false;

                                    if ($payment->month) {
                                        $deadline = \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->startOfMonth();
                                        $isLate = now()->gt($deadline) && $payment->status != 'paid' && !$payment->proof_file;
                                    }
                                @endphp

                                <span class="px-2 py-1 text-white rounded text-xs
                                    @if($payment->status == 'paid') bg-green-500
                                    @elseif($isLate) bg-red-600
                                    @else bg-yellow-500
                                    @endif">

                                    @if($payment->status == 'paid')
                                        Lunas
                                    @elseif($payment->proof_file)
                                        Pending
                                    @elseif($isLate)
                                        Telat
                                    @else
                                        Pending
                                    @endif

                                </span>
                            </td>

                            {{-- NOMINAL --}}
                            <td class="p-3 text-right">
                                Rp {{ number_format($payment->original_amount) }}
                            </td>

                            {{-- BUKTI --}}
                            <td class="p-3">
                                @if($payment->proof_file)
                                    <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                        class="object-cover w-12 h-12 border rounded cursor-pointer"
                                        onclick="window.open(this.src)">
                                @else
                                    -
                                @endif
                            </td>

                            {{-- TANGGAL --}}
                            <td class="p-3">
                                @if($payment->paid_at)
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-3 text-center text-gray-500">
                                Belum ada data iuran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr class="font-semibold border-t bg-gray-50">

                            {{-- LABEL --}}
                            <td colspan="3" class="p-3 text-right text-gray-600">
                                Total
                            </td>

                            {{-- TOTAL TAGIHAN --}}
                            <td class="p-3 text-right">
                                <div class="text-sm text-gray-500">Tagihan</div>
                                <div class="font-bold">
                                    Rp {{ number_format($payments->sum('original_amount')) }}
                                </div>
                            </td>

                            {{-- TOTAL DIBAYAR --}}
                            <td class="p-3 text-left">
                                <div class="text-sm text-green-600">Dibayar</div>
                                <div class="font-bold text-green-600">
                                    Rp {{ number_format($payments->where('status','paid')->sum('original_amount')) }}
                                </div>
                            </td>

                        </tr>
                    </tfoot>
                </table>
                @endrole("orang_tua")
            </div>

        </div>
    </div>
</x-app-layout>