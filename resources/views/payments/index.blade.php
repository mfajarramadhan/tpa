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
           <div class="grid grid-cols-2 gap-4 mb-4">
    
                <div class="p-4 text-red-700 bg-red-100 rounded">
                    <strong>Total Tunggakan</strong><br>
                    Rp {{ number_format($totalUnpaid) }}
                </div>

                <div class="p-4 text-green-700 bg-green-100 rounded">
                    <strong>Total Sudah Dibayar</strong><br>
                    Rp {{ number_format($totalPaid) }}
                </div>

            </div>

            {{-- BUTTON TAMBAH --}}
            @role('orang_tua')
                <div class="mb-4">
                    <a href="{{ route('payments.create') }}"
                       class="px-4 py-2 text-white transition bg-blue-600 rounded hover:bg-blue-700">
                        Upload Bukti Pembayaran
                    </a>
                </div>
            @endrole

            {{-- FILTER STATUS PEMBAYARAN --}}
            <form method="GET" class="flex gap-2 mb-4">
                <input type="month" name="month" value="{{ request('month') }}"
                    class="px-3 py-2 border rounded">

                <select name="status" class="px-3 py-2 border rounded">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                        Paid
                    </option>
                </select>

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Filter
                </button>
            </form>

            {{-- TABLE --}}
            <div class="overflow-hidden bg-white rounded shadow">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="text-left">
                            <th class="p-3">Nama</th>
                            <th class="p-3">Bulan</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3 text-right">Nominal</th>
                            <th class="p-3">Bukti</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($payments as $payment)
                        <tr class="border-t hover:bg-gray-50">

                            {{-- NAMA --}}
                            <td class="p-3">
                                <a href="{{ route('payments.show', $payment->student->id) }}"
                                class="text-blue-600 underline">
                                    {{ $payment->student->name }}
                                </a>
                            </td>

                            {{-- BULAN --}}
                            <td class="p-3">
                                @if($payment->type == 'registration')
                                    <span class="text-blue-600">Pendaftaran</span>
                                @else
                                    {{ $payment->month }}
                                @endif
                            </td>

                            {{-- TANGGAL --}}
                            <td class="p-3">
                                {{ $payment->created_at->format('d-m-Y') }}
                            </td>

                            {{-- NOMINAL --}}
                            <td class="p-3 text-right">
                                Rp {{ number_format($payment->amount) }}
                                <div class="text-xs text-gray-500">
                                    (Asli: Rp {{ number_format($payment->original_amount) }})
                                </div>
                            </td>

                            {{-- BUKTI --}}
                            <td class="p-3">
                                @if($payment->proof_file)
                                    @php
                                        $ext = pathinfo($payment->proof_file, PATHINFO_EXTENSION);
                                    @endphp

                                    @if(in_array($ext, ['jpg','jpeg','png']))
                                        <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                            class="object-cover w-16 h-16 border rounded cursor-pointer"
                                            onclick="window.open(this.src)">
                                    @elseif($ext == 'pdf')
                                        <a href="{{ asset('storage/' . $payment->proof_file) }}"
                                        target="_blank"
                                        class="text-red-600 underline">
                                            Lihat PDF
                                        </a>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="p-3 text-center">
                                @php
                                    $isLate = false;

                                    if ($payment->type == 'monthly' && $payment->month) {
                                        $deadline = \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->startOfMonth();
                                        $isLate = now()->gt($deadline) && $payment->status != 'paid';
                                    }
                                @endphp

                                <span class="px-2 py-1 text-white rounded text-xs
                                    @if($payment->status == 'paid') bg-green-500
                                    @elseif($isLate) bg-red-600
                                    @else bg-yellow-500
                                    @endif">

                                    @if($payment->status == 'paid')
                                        Lunas
                                    @elseif($isLate)
                                        Telat
                                    @elseif($payment->type == 'registration')
                                        Menunggu
                                    @else
                                        Pending
                                    @endif

                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">

                                    @role('superadmin')
                                        @if($payment->status != 'paid')
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                            class="px-2 py-1 text-white bg-yellow-500 rounded">
                                                Edit
                                            </a>
                                        @endif
                                    @endrole

                                    @role('superadmin|guru')
                                        @if($payment->status == 'pending')
                                            <form method="POST" action="{{ route('payments.approve', $payment->id) }}">
                                                @csrf
                                                <button class="px-2 py-1 text-white bg-green-600 rounded">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif
                                    @endrole

                                    @role('superadmin')
                                        @if($payment->status == 'paid')
                                            <form method="POST" action="{{ route('payments.unapprove', $payment->id) }}">
                                                @csrf
                                                <button class="px-2 py-1 text-white bg-red-600 rounded">
                                                    Unapprove
                                                </button>
                                            </form>
                                        @endif
                                    @endrole

                                </div>
                            </td>

                        </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>