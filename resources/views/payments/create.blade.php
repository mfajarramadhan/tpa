<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Upload Bukti Pembayaran</h2>
    </x-slot>

    <div class="max-w-lg py-6 mx-auto">

        <form method="POST"
              action="{{ route('payments.store') }}"
              enctype="multipart/form-data"
              class="p-5 bg-white rounded shadow">

            @csrf

            {{-- HIDDEN --}}
            <input type="hidden" name="payment_id" value="{{ $payment->id }}">

            {{-- INFO --}}
            <div class="mb-4">
                <p><strong>Nama:</strong> {{ $payment->student->name }}</p>
                <p><strong>Bulan:</strong>
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->format('F Y') }}
                </p>
                <p><strong>Nominal:</strong> Rp {{ number_format($payment->original_amount) }}</p>
            </div>

            {{-- UPLOAD --}}
            <div class="mb-3">
                <label>Bukti Pembayaran</label>
                <input type="file"
                       name="proof_file"
                       required
                       class="w-full p-2 border rounded">
            </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Upload Bukti
            </button>

        </form>

    </div>
</x-app-layout>