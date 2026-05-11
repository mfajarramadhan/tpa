<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Bayar Iuran</h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('payments.index') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>
        
        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST"
                action="{{ route('payments.store') }}"
                enctype="multipart/form-data">

                @csrf

                {{-- HIDDEN --}}
                <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-gray-100">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:upload-bold-duotone"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-slate-800">
                            Bayar Iuran
                        </h2>

                        <p class="text-sm text-slate-500">
                            Upload bukti pembayaran iuran bulanan
                        </p>

                    </div>

                </div>

                {{-- INFO --}}
                <div class="p-5 mb-6 border border-gray-100 rounded-2xl bg-gray-50">

                    <div class="grid gap-4 md:text-center md:grid-cols-3">

                        {{-- NAMA --}}
                        <div>

                            <p class="mb-1 text-xs font-semibold tracking-wide uppercase text-slate-500">
                                Nama Siswa
                            </p>

                            <p class="font-bold text-slate-800">
                                {{ $payment->student->name }}
                            </p>

                        </div>

                        {{-- BULAN --}}
                        <div>

                            <p class="mb-1 text-xs font-semibold tracking-wide uppercase text-slate-500">
                                Bulan
                            </p>

                            <p class="font-bold text-slate-800">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $payment->month)->format('F Y') }}
                            </p>

                        </div>

                        {{-- NOMINAL --}}
                        <div>

                            <p class="mb-1 text-xs font-semibold tracking-wide uppercase text-slate-500">
                                Nominal
                            </p>

                            <p class="font-bold text-slate-800">
                                Rp {{ number_format($payment->original_amount) }}
                            </p>

                        </div>

                    </div>

                </div>

                @if($payment->proof_file)

                    @php
                        $proofUrl = asset('storage/' . $payment->proof_file);
                        $ext = strtolower(pathinfo($payment->proof_file, PATHINFO_EXTENSION));
                    @endphp

                    <div class="p-5 mb-6 border border-yellow-200 shadow-sm rounded-2xl bg-yellow-50">

                        {{-- HEADER --}}
                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-sm font-semibold text-yellow-800">
                                    Bukti Pembayaran Sebelumnya
                                </p>

                            </div>

                            {{-- STATUS --}}
                            @if($payment->status == 'rejected')

                                <span class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-lg">
                                    Ditolak
                                </span>

                            @endif

                        </div>

                        {{-- PREVIEW --}}
                        <div class="mt-4">

                            {{-- IMAGE --}}
                            @if(in_array($ext, ['jpg','jpeg','png']))

                                <img src="{{ $proofUrl }}"
                                    onclick="openProofPreview('image', '{{ $proofUrl }}')"
                                    class="object-cover w-32 h-32 transition border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                            {{-- PDF --}}
                            @elseif($ext === 'pdf')

                                <div
                                    onclick="openProofPreview('pdf', '{{ $proofUrl }}')"
                                    class="flex flex-col items-center justify-center w-32 h-32 transition bg-white border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                                    <div class="text-4xl">
                                        📄
                                    </div>

                                    <p class="mt-2 text-xs font-semibold text-red-500">
                                        PDF
                                    </p>

                                </div>

                            @endif

                        </div>

                        {{-- REJECT NOTE --}}
                        @if($payment->status == 'rejected' && $payment->reject_reason)

                            <div class="p-3 mt-4 border border-red-200 rounded-xl bg-red-50">

                                <p class="text-xs font-semibold text-red-600">
                                    Alasan Penolakan
                                </p>

                                <p class="mt-1 text-sm text-red-500">
                                    {{ $payment->reject_reason }}
                                </p>

                            </div>

                        @endif

                    </div>

                @endif

                {{-- BUKTI PEMBAYARAN --}}
                <div class="mb-4">

                    <label class="block mb-2 text-sm font-semibold">
                        Bukti Pembayaran
                    </label>

                    {{-- PREVIEW BARU --}}
                    <div id="preview_proof_wrapper"
                        class="hidden mb-4">

                        <p class="mb-2 text-xs font-semibold text-slate-500">
                            File Baru
                        </p>

                        {{-- IMAGE --}}
                        <img id="preview_proof"
                            onclick="openProofPreview('image', this.src)"
                            class="hidden object-cover w-32 h-32 transition border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                        {{-- PDF --}}
                        <div id="preview_proof_pdf"
                            onclick="openProofPreview('pdf', this.dataset.src)"
                            class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border cursor-pointer rounded-xl hover:scale-105 hover:shadow-md">

                            <div class="text-4xl">
                                📄
                            </div>

                            <p class="mt-2 text-xs font-semibold text-red-500">
                                PDF
                            </p>

                            <p class="mt-1 text-[10px] text-gray-500">
                                Klik untuk preview
                            </p>

                        </div>

                    </div>

                    {{-- INPUT --}}
                    <input type="file"
                        name="proof_file"
                        required
                        onchange="previewProof(event)"
                        class="w-full p-2 border rounded-xl @error('proof_file') border-red-500 @enderror">

                    <p class="mt-1 text-xs text-gray-500">
                        Maksimal ukuran file 2MB
                    </p>

                    @error('proof_file')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- BUTTON --}}
                <div class="mt-6">

                    <button class="shadow-sm btn-primary">
                        Upload Bukti
                    </button>

                </div>

            </form>

        </div>
    </div>

    {{-- ================= PROOF PREVIEW MODAL ================= --}}
    <div id="proofPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closeProofPreview()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="proofPreviewImage"
                class="hidden object-contain w-full max-h-[90vh] rounded-lg shadow-2xl">

            {{-- PDF --}}
            <iframe id="proofPreviewPdf"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>
</x-app-layout>

<script>

    function openProofPreview(type, src) {

        const modal = document.getElementById('proofPreviewModal');

        const image = document.getElementById('proofPreviewImage');
        const pdf = document.getElementById('proofPreviewPdf');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (type === 'image') {

            image.src = src;
            image.classList.remove('hidden');

        }

        // PDF
        if (type === 'pdf') {

            pdf.src = src;
            pdf.classList.remove('hidden');

        }

        modal.style.display = 'flex';
    }

    function closeProofPreview() {

        const modal = document.getElementById('proofPreviewModal');

        document.getElementById('proofPreviewImage').src = '';
        document.getElementById('proofPreviewPdf').src = '';

        modal.style.display = 'none';
    }

    // klik backdrop
    document.getElementById('proofPreviewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'proofPreviewModal') {
                closeProofPreview();
            }

    });

    function previewProof(event) {

        const file = event.target.files[0];

        if (!file) return;

        const wrapper = document.getElementById('preview_proof_wrapper');

        const image = document.getElementById('preview_proof');
        const pdf = document.getElementById('preview_proof_pdf');

        wrapper.classList.remove('hidden');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (file.type.startsWith('image/')) {

            image.src = URL.createObjectURL(file);

            image.classList.remove('hidden');

        }

        // PDF
        else if (file.type === 'application/pdf') {

            pdf.dataset.src = URL.createObjectURL(file);

            pdf.classList.remove('hidden');
            pdf.classList.add('flex');

        }

    }


</script>