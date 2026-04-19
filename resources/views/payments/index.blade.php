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
                        @php
                            $status = $data['status'];

                            if ($status == 'Lunas') {
                                $class = 'bg-green-500';
                            } elseif ($status == 'Menunggak') {
                                $class = 'bg-red-600';
                            } elseif ($status == 'Menunggu Konfirmasi') {
                                $class = 'bg-yellow-500';
                            } elseif ($status == 'Tidak ada tagihan') {
                                $class = 'bg-black';
                            } else {
                                $class = 'bg-gray-500'; // Belum Bayar
                            }
                        @endphp

                        <span class="px-2 py-1 text-white rounded text-xs {{ $class }}">
                            {{ $status }}
                        </span>
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
                            <th class="p-3 text-center">Aksi</th>
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

                            {{-- NOMINAL --}}
                            <td class="p-3 text-right">
                                Rp {{ number_format($payment->final_amount) }}
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

                            <td class="p-3 text-center">

                                {{-- ✔ SUDAH LUNAS --}}
                                @if($payment->status == 'paid')
                                    <span class="text-gray-400">-</span>

                                {{-- ❌ DITOLAK --}}
                                @elseif($payment->status == 'rejected')

                                    <a href="{{ route('payments.create', ['student_id' => $payment->student_id]) }}"
                                    class="px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Perbaiki & Bayar
                                    </a>

                                {{-- ⏳ SUDAH UPLOAD (MENUNGGU) --}}
                                @elseif($payment->proof_file)
                                    <span class="text-gray-400">-</span>

                                {{-- ❌ BELUM BAYAR --}}
                                @else
                                    <span class="text-gray-400">-</span> 
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