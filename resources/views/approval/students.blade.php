<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Iuran Bulanan</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

        @foreach ($students as $student)
            <div class="p-5 mb-4 bg-white rounded shadow">

                {{-- IDENTITAS --}}
                <div class="mb-3">
                    <p class="text-lg font-bold">{{ $student->name }}</p>
                    <p class="text-sm text-gray-500">
                        Orang Tua: {{ $student->parent->name }}
                    </p>
                </div>

                {{-- DETAIL DATA --}}
                <div class="grid grid-cols-2 gap-3 text-sm">

                    <p><strong>NIK:</strong> {{ $student->nik }}</p>
                    <p><strong>Tanggal Lahir:</strong> {{ $student->birth_date }}</p>

                    <p><strong>Jenis Kelamin:</strong> {{ $student->gender }}</p>
                    <p><strong>Sekolah Asal:</strong> {{ $student->school_origin }}</p>
                    <p><strong>Alamat:</strong> {{ $student->parent->address }}</p>

                </div>

                {{-- FILE --}}
                <div class="mt-3 space-x-3">

                    @if($student->kk_file)
                        <a href="{{ asset('storage/' . $student->kk_file) }}"
                        target="_blank"
                        class="text-sm text-blue-600 underline">
                            Lihat KK
                        </a>
                    @endif

                    @if($student->birth_certificate_file)
                        <a href="{{ asset('storage/' . $student->birth_certificate_file) }}"
                        target="_blank"
                        class="text-sm text-blue-600 underline">
                            Lihat Akta
                        </a>
                    @endif

                    @php
                        $payment = $student->payments->where('type', 'registration')->first();
                    @endphp

                    <div class="mt-3">

                        <p class="text-sm font-semibold">Bukti Pembayaran:</p>

                        {{-- 🔥 PREVIEW --}}
                        @if($payment && $payment->proof_file)

                            <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                class="w-32 mt-2 border rounded cursor-pointer"
                                onclick="window.open(this.src)">

                            <div>
                                <a href="{{ asset('storage/' . $payment->proof_file) }}"
                                target="_blank"
                                class="text-sm text-blue-600 underline">
                                    Lihat ukuran penuh
                                </a>
                            </div>

                        @else
                            <p class="text-sm text-gray-500">Belum upload bukti</p>
                        @endif

                        {{-- 🔥 STATUS --}}
                        <div class="mt-2">

                            @if(!$payment || !$payment->proof_file)
                                <span class="text-sm text-gray-500">
                                    ⏳ Menunggu pembayaran
                                </span>

                            @elseif($payment->status == 'pending')
                                <span class="text-sm text-yellow-600">
                                    ⏳ Menunggu verifikasi admin
                                </span>

                            @elseif($payment->status == 'paid')
                                <span class="text-sm text-green-600">
                                    ✔ Pembayaran terverifikasi
                                </span>

                            @endif

                        </div>

                    </div>

                {{-- ACTION --}}
                <div class="flex items-center gap-3 mt-4">

                    {{-- PILIH KELAS --}}
                    <form method="POST"
                        action="{{ route('approval.students.approve', $student->id) }}">
                        @csrf

                        <select name="classroom_id" class="p-2 border rounded">
                            @foreach($classrooms as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>

                        @if($payment && $payment->proof_file)
                            <button class="px-3 py-1 text-white bg-green-600 rounded">
                                Approve
                            </button>
                        @else
                            <button class="px-3 py-1 text-white bg-gray-400 rounded" disabled>
                                Menunggu Pembayaran
                            </button>
                        @endif
                    </form>

                    {{-- REJECT --}}
                    <form method="POST"
                        action="{{ route('approval.students.reject', $student->id) }}">
                        @csrf

                        <input type="text"
                            name="reject_reason"
                            placeholder="Alasan"
                            class="p-2 text-sm border rounded"
                            required>

                        <button class="px-3 py-1 text-white bg-red-600 rounded">
                            Reject
                        </button>
                    </form>

                </div>

            </div>
        @endforeach
        </div>
    </div>
</x-app-layout>