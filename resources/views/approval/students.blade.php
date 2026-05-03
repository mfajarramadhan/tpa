<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Approval Pendaftaran</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

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

            @forelse ($students as $student)
                @php
                    $payment = $student->payments->where('type', 'registration')->first();
                @endphp

                <div class="p-6 bg-white shadow-sm rounded-2xl">

                    {{-- IDENTITAS --}}
                    <div class="pb-4 mb-4 border-b">
                        <h3 class="text-lg font-bold">{{ $student->name }}</h3>
                        <p class="text-sm text-gray-500">
                            Orang Tua: {{ $student->parent->name }}
                        </p>
                    </div>

                    {{-- DETAIL --}}
                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                        <p><span class="font-semibold">NIK:</span> {{ $student->nik }}</p>
                        <p><span class="font-semibold">Tanggal Lahir:</span> {{ $student->birth_date }}</p>
                        <p><span class="font-semibold">Jenis Kelamin:</span> {{ $student->gender }}</p>
                        <p><span class="font-semibold">Sekolah Asal:</span> {{ $student->school_origin }}</p>
                        <p class="md:col-span-2">
                            <span class="font-semibold">Alamat:</span> {{ $student->parent->address }}
                        </p>
                    </div>

                    {{-- DOKUMEN + PEMBAYARAN --}}
                    <div class="pt-4 mt-4 border-t">
                        <p class="mb-3 text-sm font-semibold">Dokumen</p>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                            {{-- KK --}}
                            <div class="text-center">
                                <p class="mb-2 text-xs font-semibold text-gray-600">KK</p>

                                @if($student->kk_file)
                                    <img src="{{ asset('storage/' . $student->kk_file) }}"
                                        class="object-cover w-32 mx-auto border rounded-lg cursor-pointer h-28 hover:shadow"
                                        onclick="window.open(this.src)">
                                    <a href="{{ asset('storage/' . $student->kk_file) }}"
                                    target="_blank"
                                    class="block mt-1 text-xs text-blue-600 underline">
                                        Lihat ukuran penuh
                                    </a>
                                @else
                                    <p class="text-xs text-gray-400">Belum upload</p>
                                @endif
                            </div>

                            {{-- AKTA --}}
                            <div class="text-center">
                                <p class="mb-2 text-xs font-semibold text-gray-600">Akta</p>

                                @if($student->birth_certificate_file)
                                    <img src="{{ asset('storage/' . $student->birth_certificate_file) }}"
                                        class="object-cover w-32 mx-auto border rounded-lg cursor-pointer h-28 hover:shadow"
                                        onclick="window.open(this.src)">
                                    <a href="{{ asset('storage/' . $student->birth_certificate_file) }}"
                                    target="_blank"
                                    class="block mt-1 text-xs text-blue-600 underline">
                                        Lihat ukuran penuh
                                    </a>
                                @else
                                    <p class="text-xs text-gray-400">Belum upload</p>
                                @endif
                            </div>

                            {{-- BUKTI BAYAR --}}
                            <div class="text-center">
                                <p class="mb-2 text-xs font-semibold text-gray-600">Bukti Bayar</p>

                                @if($payment && $payment->proof_file)
                                    <img src="{{ asset('storage/' . $payment->proof_file) }}"
                                        class="object-cover w-32 mx-auto border rounded-lg cursor-pointer h-28 hover:shadow"
                                        onclick="window.open(this.src)">
                                    <a href="{{ asset('storage/' . $payment->proof_file) }}"
                                    target="_blank"
                                    class="block mt-1 text-xs text-blue-600 underline">
                                        Lihat ukuran penuh
                                    </a>
                                @else
                                    <p class="text-xs text-gray-400">Belum upload</p>
                                @endif
                            </div>

                        </div>

                        {{-- STATUS --}}
                        <div class="mt-4">
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
                            class="flex items-center gap-2" 
                            onsubmit="return confirm('Yakin ingin menyetujui siswa ini?')">
                            @csrf

                            {{-- APPROVE MODAL --}}
                            <div x-data="{ openApprove: false }" class="flex items-center gap-2">

                                {{-- BUTTON --}}
                                @if($payment && $payment->proof_file)
                                    <button @click="openApprove = true"
                                            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                        Approve
                                    </button>
                                @else
                                    <button class="px-4 py-2 text-sm text-white bg-gray-400 rounded-lg cursor-not-allowed" disabled>
                                        Menunggu Pembayaran
                                    </button>
                                @endif

                                {{-- MODAL --}}
                                <div x-show="openApprove"
                                    x-transition
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">

                                    <div @click.outside="openApprove = false"
                                        class="w-full max-w-md p-5 bg-white shadow-xl rounded-xl">

                                        <h2 class="mb-3 text-lg font-semibold">Pilih Kelas</h2>

                                        <form method="POST"
                                            action="{{ route('approval.students.approve', $student->id) }}">
                                            @csrf

                                            <select name="classroom_id"
                                                    class="w-full p-2 text-sm border rounded-lg focus:ring focus:ring-blue-200"
                                                    required>
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($classrooms as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>

                                            <div class="flex justify-end gap-2 mt-4">
                                                <button type="button"
                                                        @click="openApprove = false"
                                                        class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                                                    Batal
                                                </button>

                                                <button class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                    Approve
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>
                        </form>

                        {{-- REJECT --}}
                        <form method="POST"
                            action="{{ route('approval.students.reject', $student->id) }}"
                            class="flex items-center gap-2"
                            onsubmit="return confirm('Yakin ingin menolak siswa ini?')">
                            @csrf

                            <div x-data="{ openReject: false }" class="flex items-center gap-2">

                            {{-- BUTTON OPEN MODAL --}}
                            <button @click="openReject = true"
                                    class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                                Reject
                            </button>

                            {{-- MODAL --}}
                            <div x-show="openReject"
                                x-transition
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">

                                <div @click.outside="openReject = false"
                                    class="w-full max-w-md p-5 bg-white shadow-xl rounded-xl">

                                    <h2 class="mb-3 text-lg font-semibold">Alasan Penolakan</h2>

                                    <form method="POST"
                                        action="{{ route('approval.students.reject', $student->id) }}">
                                        @csrf

                                        <textarea name="reject_reason"
                                                rows="3"
                                                placeholder="Masukkan alasan penolakan..."
                                                class="w-full p-2 text-sm border rounded-lg resize-none focus:ring focus:ring-red-200"
                                                required></textarea>

                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button"
                                                    @click="openReject = false"
                                                    class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                                                Batal
                                            </button>

                                            <button class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                Kirim
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                        </form>

                    </div>
                </div>
            @empty
                <div class="p-6 text-center bg-white shadow-sm rounded-2xl">
                    Tidak ada data approval
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>