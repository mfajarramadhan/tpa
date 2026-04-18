<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Orang Tua</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            <h3 class="mb-6 text-lg font-semibold">Daftar Anak</h3>

            <div class="grid gap-6 md:grid-cols-2">
                @foreach($students as $student)
                    <div class="p-5 bg-white shadow rounded-2xl">

                        {{-- HEADER --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-bold">
                                    {{ $student->name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ $student->classroom->name ?? 'Belum terdaftar kelas' }}
                                </p>
                            </div>

                            {{-- STATUS --}}
                            <div>
                                @if($student->status == 'nonaktif')
                                    <span class="px-3 py-1 text-xs text-white bg-yellow-500 rounded-full">
                                        Menunggu
                                    </span>

                                @elseif($student->status == 'aktif')
                                    <span class="px-3 py-1 text-xs text-white bg-green-500 rounded-full">
                                        Aktif
                                    </span>

                                @elseif($student->status == 'ditolak')
                                    <span class="px-3 py-1 text-xs text-white bg-red-500 rounded-full">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- INFO --}}
                        <div class="mt-3 text-sm text-gray-600">
                            <p>Asal Sekolah: {{ $student->school_origin }}</p>
                        </div>

                        {{-- REJECT REASON --}}
                        @if($student->status == 'ditolak')
                            <div class="p-2 mt-3 text-sm text-red-700 bg-red-100 rounded">
                                {{ $student->reject_reason }}
                            </div>
                        @endif

                        {{-- DIVIDER --}}
                        <div class="my-4 border-t"></div>

                        {{-- IURAN --}}
                        <div>
                            @if($student->status == 'ditolak')

                                <a href="{{ route('students.reapply', $student->id) }}"
                                    class="block w-full p-2 text-center text-white transition bg-red-500 rounded-xl hover:bg-red-600">

                                        <div class="text-lg font-bold">
                                            Perbaiki & Daftar Ulang
                                        </div>
                                    </a>

                            @else
                            
                                <p class="mb-2 text-sm font-semibold">Status Iuran</p>

                                @php
                                    $monthlyPayments = $student->payments->where('type', 'monthly');

                                    $sisaTagihan = $monthlyPayments
                                        ->where('status', 'pending')
                                        ->whereNull('proof_file') // belum bayar
                                        ->sum('original_amount');

                                    $sudahDibayar = $monthlyPayments
                                        ->where('status', 'paid')
                                        ->sum('original_amount');
                                @endphp

                                <div class="p-3 rounded bg-gray-50">

                                    @if($monthlyPayments->isEmpty())
                                        <p class="text-xs text-gray-400">Belum ada data iuran</p>
                                    @else

                                        <p class="text-sm text-gray-500">Sisa Tagihan</p>

                                        <p class="text-lg font-bold text-red-600">
                                            Rp {{ number_format($sisaTagihan) }}
                                        </p>

                                        @if($sisaTagihan == 0)
                                            <p class="mt-1 text-xs text-green-600">✔ Semua iuran sudah lunas</p>
                                        @endif

                                    @endif

                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>