<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Iuran Bulanan</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto space-y-6 max-w-7xl">

        @foreach ($students as $student)
            <div class="p-6 bg-white shadow-sm rounded-2xl">

                {{-- IDENTITAS --}}
                <div class="pb-4 mb-4 border-b">
                    <h3 class="text-lg font-bold">{{ $student->name }}</h3>
                    <p class="text-sm text-gray-500">
                        Orang Tua: {{ $student->parent->name }}
                    </p>
                </div>

                {{-- DETAIL DATA --}}
                <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                    <p><span class="font-semibold">NIK:</span> {{ $student->nik }}</p>
                    <p><span class="font-semibold">Tanggal Lahir:</span> {{ $student->birth_date }}</p>
                    <p><span class="font-semibold">Jenis Kelamin:</span> {{ $student->gender }}</p>
                    <p><span class="font-semibold">Sekolah Asal:</span> {{ $student->school_origin }}</p>
                    <p class="md:col-span-2">
                        <span class="font-semibold">Alamat:</span> {{ $student->parent->address }}
                    </p>
                </div>

                {{-- FILE --}}
                <div class="pt-4 mt-4 border-t">
                    <p class="mb-2 text-sm font-semibold">Dokumen</p>

                    <div class="flex flex-wrap gap-3">
                        @if($student->kk_file)
                            <a href="{{ asset('storage/' . $student->kk_file) }}"
                               target="_blank"
                               class="px-3 py-1 text-sm text-blue-600 rounded bg-blue-50 hover:bg-blue-100">
                                Lihat KK
                            </a>
                        @endif

                        @if($student->birth_certificate_file)
                            <a href="{{ asset('storage/' . $student->birth_certificate_file) }}"
                               target="_blank"
                               class="px-3 py-1 text-sm text-blue-600 rounded bg-blue-50 hover:bg-blue-100">
                                Lihat Akta
                            </a>
                        @endif
                    </div>
                </div>

                @php
                    $payment = $student->payments->where('type', 'registration')->first();
                @endphp

                {{-- PEMBAYARAN --}}
                <div class="pt-4 mt-4 border-t">
                    <p class="text-sm font-semibold">Bukti Pembayaran</p>

                    @if($payment && $payment->proof_file)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                 class="w-32 border rounded-lg cursor-pointer hover:shadow"
                                 onclick="window.open(this.src)">

                            <a href="{{ asset('storage/' . $payment->proof_file) }}"
                               target="_blank"
                               class="block mt-1 text-xs text-blue-600 underline">
                                Lihat ukuran penuh
                            </a>
                        </div>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Belum upload bukti</p>
                    @endif

                    {{-- STATUS --}}
                    <div class="mt-3">
                        @if(!$payment || !$payment->proof_file)
                            <span class="px-3 py-1 text-xs text-gray-600 bg-gray-100 rounded-full">
                                ⏳ Menunggu pembayaran
                            </span>

                        @elseif($payment->status == 'pending')
                            <span class="px-3 py-1 text-xs text-yellow-700 bg-yellow-100 rounded-full">
                                ⏳ Menunggu verifikasi
                            </span>

                        @elseif($payment->status == 'paid')
                            <span class="px-3 py-1 text-xs text-green-700 bg-green-100 rounded-full">
                                ✔ Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="flex flex-col gap-3 pt-4 mt-4 border-t md:flex-row md:items-center md:justify-between">

                    {{-- APPROVE --}}
                    <form method="POST"
                          action="{{ route('approval.students.approve', $student->id) }}"
                          class="flex items-center gap-2">
                        @csrf

                        <select name="classroom_id"
                                class="p-2 text-sm border rounded-lg focus:ring focus:ring-blue-200">
                            @foreach($classrooms as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>

                        @if($payment && $payment->proof_file)
                            <button class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                Approve
                            </button>
                        @else
                            <button class="px-4 py-2 text-sm text-white bg-gray-400 rounded-lg cursor-not-allowed" disabled>
                                Menunggu Pembayaran
                            </button>
                        @endif
                    </form>

                    {{-- REJECT --}}
                    <form method="POST"
                          action="{{ route('approval.students.reject', $student->id) }}"
                          class="flex items-center gap-2">
                        @csrf

                        <input type="text"
                               name="reject_reason"
                               placeholder="Alasan penolakan"
                               class="p-2 text-sm border rounded-lg focus:ring focus:ring-red-200"
                               required>

                        <button class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Reject
                        </button>
                    </form>

                </div>

            </div>
        @endforeach

        </div>
    </div>
</x-app-layout>