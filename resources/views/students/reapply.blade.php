<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Perbaiki Data & Daftar Ulang
        </h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        {{-- ALERT --}}
        @if($student->reject_reason)
            <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                <strong>Alasan Ditolak:</strong><br>
                {{ $student->reject_reason }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('students.reapply.submit', $student->id) }}"
              enctype="multipart/form-data"
              class="p-5 bg-white rounded shadow">

            @csrf

            {{-- NAMA --}}
            <div class="mb-3">
                <label class="block text-sm">Nama Anak</label>
                <input type="text"
                       name="name"
                       value="{{ $student->name }}"
                       class="w-full p-2 border rounded"
                       required>
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label class="block text-sm">NIK</label>
                <input type="text"
                       name="nik"
                       value="{{ $student->nik }}"
                       class="w-full p-2 border rounded"
                       required>
            </div>

            {{-- TANGGAL LAHIR --}}
            <div class="mb-3">
                <label class="block text-sm">Tanggal Lahir</label>
                <input type="date"
                       name="birth_date"
                       value="{{ $student->birth_date }}"
                       class="w-full p-2 border rounded"
                       required>
            </div>

            {{-- GENDER --}}
            <div class="mb-3">
                <label class="block text-sm">Jenis Kelamin</label>
                <select name="gender" class="w-full p-2 border rounded">
                    <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            {{-- SEKOLAH --}}
            <div class="mb-3">
                <label class="block text-sm">Sekolah Asal</label>
                <input type="text"
                       name="school_origin"
                       value="{{ $student->school_origin }}"
                       class="w-full p-2 border rounded"
                       required>
            </div>

            {{-- BUKTI PEMBAYARAN --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Bukti Pembayaran (Opsional)</label>

                @php
                    $payment = \App\Models\Payment::where('student_id', $student->id)
                        ->where('type', 'registration')
                        ->first();
                @endphp

                @if($payment && $payment->proof_file)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $payment->proof_file) }}"
                            class="object-cover w-32 border rounded-lg h-28">
                        <p class="mt-1 text-xs text-gray-500">Bukti sebelumnya</p>
                    </div>
                @endif

                <input type="file"
                    name="payment_proof"
                    class="w-full p-2 text-sm border rounded-lg">

                <p class="mt-1 text-xs text-gray-400">
                    Kosongkan jika tidak ingin mengganti
                </p>
            </div>


            {{-- KK --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">KK (Opsional)</label>

                @if($student->kk_file)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $student->kk_file) }}"
                            class="object-cover w-32 border rounded-lg h-28">
                        <p class="mt-1 text-xs text-gray-500">File sebelumnya</p>
                    </div>
                @endif

                <input type="file"
                    name="kk_file"
                    class="w-full p-2 text-sm border rounded-lg">

                <p class="mt-1 text-xs text-gray-400">
                    Kosongkan jika tidak ingin mengganti
                </p>
            </div>


            {{-- AKTA --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Akta Kelahiran (Opsional)</label>

                @if($student->birth_certificate_file)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $student->birth_certificate_file) }}"
                            class="object-cover w-32 border rounded-lg h-28">
                        <p class="mt-1 text-xs text-gray-500">File sebelumnya</p>
                    </div>
                @endif

                <input type="file"
                    name="birth_certificate_file"
                    class="w-full p-2 text-sm border rounded-lg">

                <p class="mt-1 text-xs text-gray-400">
                    Kosongkan jika tidak ingin mengganti
                </p>
            </div>

            <p class="text-xs text-yellow-600">
                Jika kesalahan hanya pada data, Anda tidak perlu upload ulang bukti pembayaran
            </p>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Daftar Ulang
            </button>

        </form>

    </div>
</x-app-layout>