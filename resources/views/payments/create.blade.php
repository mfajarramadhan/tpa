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

            @if($payment->proof_file)

            <div class="p-3 mb-3 border rounded bg-yellow-50">
                <p class="text-sm font-semibold">Bukti Pembayaran Sebelumnya:</p>

                {{-- PREVIEW --}}
                <img src="{{ asset('storage/' . $payment->proof_file) }}"
                    class="w-32 mt-2 border rounded">

                {{-- LINK FULL --}}
                <div>
                    <a href="{{ asset('storage/' . $payment->proof_file) }}"
                    target="_blank"
                    class="text-sm text-blue-600 underline">
                        Lihat ukuran penuh
                    </a>
                </div>

                {{-- INFO --}}
                @if($payment->status == 'rejected')
                    <p class="mt-2 text-xs text-red-500">
                        ❌ Pembayaran sebelumnya ditolak
                    </p>

                    @if($payment->reject_reason)
                        <p class="text-xs text-red-500">
                            Alasan: {{ $payment->reject_reason }}
                        </p>
                    @endif
                @endif
            </div>

        @endif

        {{-- BUKTI PEMBAYARAN --}}
            <div class="mt-4">
                <label class="block mb-1 text-sm font-semibold">Bukti Pembayaran</label>

                <img id="preview_proof" class="hidden w-32 mb-2 border rounded-lg"/>

                <input type="file" name="proof_file" required
                        onchange="previewImage(event, 'preview_proof')"
                        class="w-full p-2 border rounded-lg">

                <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                @error('proof_file')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                Upload Bukti
            </button>

        </form>

    </div>
</x-app-layout>

<script>
function previewImage(event, id) {
    const input = event.target;
    const preview = document.getElementById(id);

    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('hidden');
    }
}
</script>