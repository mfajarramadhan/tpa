<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Daftar Ulang
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        
        {{-- BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>

        {{-- ALERT --}}
        @if($student->reject_reason)
            <div class="p-4 mb-6 border border-red-200 rounded-xl bg-red-50">
                <p class="mb-1 text-sm font-semibold text-red-600">
                    Alasan Penolakan
                </p>
                {{ $student->reject_reason }}
            </div>
        @endif

        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST"
                action="{{ route('students.reapply.submit', $student->id) }}"
                enctype="multipart/form-data">

                @csrf

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="block mb-1 text-sm font-semibold">Nama Anak</label>
                    <input type="text"
                        name="name"
                        value="{{ $student->name }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                        required>
                </div>

                {{-- NISN --}}
                <div class="mb-3">
                    <label class="block mb-1 text-sm font-semibold">NISN (Nomor Induk Siswa Nasional)</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                           maxlength="10" 
                           inputmode="numeric"
                           pattern="[0-9]*"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    @error('nisn')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="mb-3">
                    <label class="block mb-1 text-sm font-semibold">Tanggal Lahir</label>
                    <input type="date"
                        name="birth_date"
                        value="{{ $student->birth_date }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                        required>
                </div>

                {{-- GENDER --}}
                <div class="mb-3">
                    <label class="block mb-1 text-sm font-semibold">Jenis Kelamin</label>
                    <select name="gender" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                        <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- SEKOLAH --}}
                <div class="mb-3">
                    <label class="block mb-1 text-sm font-semibold">Sekolah Asal</label>
                    <input type="text"
                        name="school_origin"
                        value="{{ $student->school_origin }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                        required>
                </div>

                {{-- KK --}}
                <div class="mt-4">
                    <label class="block text-sm font-semibold">Kartu Keluarga</label>
                    <p class="mb-2 text-xs text-gray-500">*Tidak perlu upload ulang jika dokumen sebelumnya sudah sesuai!</p>

                    <div class="mb-3">

                        {{-- PREVIEW AREA --}}
                        <div class="flex flex-col gap-4 mb-4 md:flex-row">

                            {{-- FILE LAMA --}}
                            @if($student->kk_file)

                                @php
                                    $kkUrl = asset('storage/' . $student->kk_file);
                                    $kkExt = strtolower(pathinfo($student->kk_file, PATHINFO_EXTENSION));
                                @endphp

                                <div>

                                    <p class="mb-2 text-xs font-semibold text-slate-500">
                                        File Sebelumnya
                                    </p>

                                    {{-- IMAGE --}}
                                    @if(in_array($kkExt, ['jpg','jpeg','png']))

                                        <img src="{{ $kkUrl }}"
                                            onclick="openUploadPreview('image', '{{ $kkUrl }}')"
                                            class="object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    {{-- PDF --}}
                                    @elseif($kkExt === 'pdf')

                                        <div
                                            onclick="openUploadPreview('pdf', '{{ $kkUrl }}')"
                                            class="flex flex-col items-center justify-center w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                            <div class="text-4xl">
                                                📄
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            @endif

                            {{-- PREVIEW BARU --}}
                            <div id="preview_kk_wrapper"
                                class="hidden">

                                <p class="mb-2 text-xs font-semibold text-slate-500">
                                    Preview Baru
                                </p>

                                {{-- IMAGE --}}
                                <img id="preview_kk"
                                    class="hidden object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md"
                                    onclick="openUploadPreview('image', this.src)">

                                {{-- PDF --}}
                                <div id="preview_kk_pdf"
                                    onclick="openUploadPreview('pdf', this.dataset.src)"
                                    class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    <div class="text-4xl">
                                        📄
                                    </div>

                                    <p class="mt-2 text-xs font-semibold text-red-500">
                                        PDF
                                    </p>

                                </div>

                            </div>

                        </div>

                        <div id="preview_kk_wrapper"
                            class="hidden">

                            <img id="preview_kk"
                                class="object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md"
                                onclick="openUploadPreview('image', this.src)">

                            <div id="preview_kk_pdf"
                                onclick="openUploadPreview('pdf', this.dataset.src)"
                                class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                <div class="text-4xl">📄</div>

                                <p class="mt-2 text-xs font-semibold text-red-500">
                                    PDF
                                </p>

                            </div>

                        </div>

                    </div>

                    <input type="file" name="kk_file"
                           onchange="previewFile(event, 'kk')"
                           class="w-full p-2 border rounded-lg">

                    <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                    @error('kk_file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- AKTA --}}
                <div class="mt-4">
                    <label class="block text-sm font-semibold">Akta Kelahiran</label>
                    <p class="mb-2 text-xs text-gray-500">*Tidak perlu upload ulang jika dokumen sebelumnya sudah sesuai!</p>

                    <div class="mb-3">

                        {{-- PREVIEW AREA --}}
                        <div class="flex flex-col gap-4 mb-4 md:flex-row">

                            {{-- FILE LAMA --}}
                            @if($student->birth_certificate_file)

                                @php
                                    $aktaUrl = asset('storage/' . $student->birth_certificate_file);
                                    $aktaExt = strtolower(pathinfo($student->birth_certificate_file, PATHINFO_EXTENSION));
                                @endphp

                                <div>

                                    <p class="mb-2 text-xs font-semibold text-slate-500">
                                        File Sebelumnya
                                    </p>

                                    {{-- IMAGE --}}
                                    @if(in_array($aktaExt, ['jpg','jpeg','png']))

                                        <img src="{{ $aktaUrl }}"
                                            onclick="openUploadPreview('image', '{{ $aktaUrl }}')"
                                            class="object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    {{-- PDF --}}
                                    @elseif($aktaExt === 'pdf')

                                        <div
                                            onclick="openUploadPreview('pdf', '{{ $aktaUrl }}')"
                                            class="flex flex-col items-center justify-center w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                            <div class="text-4xl">
                                                📄
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            @endif

                            {{-- PREVIEW BARU --}}
                            <div id="preview_akta_wrapper"
                                class="hidden">

                                <p class="mb-2 text-xs font-semibold text-slate-500">
                                    Preview Baru
                                </p>

                                {{-- IMAGE --}}
                                <img id="preview_akta"
                                    class="hidden object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md"
                                    onclick="openUploadPreview('image', this.src)">

                                {{-- PDF --}}
                                <div id="preview_akta_pdf"
                                    onclick="openUploadPreview('pdf', this.dataset.src)"
                                    class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    <div class="text-4xl">
                                        📄
                                    </div>

                                    <p class="mt-2 text-xs font-semibold text-red-500">
                                        PDF
                                    </p>

                                </div>

                            </div>

                        </div>

                    <input type="file" name="birth_certificate_file"
                           onchange="previewFile(event, 'akta')"
                           class="w-full p-2 border rounded-lg">

                    <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                    @error('birth_certificate_file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- INFO BIAYA --}}
                <div class="p-3 mt-4 text-sm bg-yellow-100 rounded-lg">
                    <strong>Biaya Pendaftaran:</strong>
                    Rp {{ number_format($fee->registration_fee) }}
                </div>
                
                {{-- BUKTI PEMBAYARAN --}}
                <div class="mt-4">
                    <label class="block mb-1 text-sm font-semibold">Bukti Pembayaran</label>

                    <div class="mb-3">

                        {{-- PREVIEW AREA --}}
                        <div class="flex flex-col gap-4 mb-4 md:flex-row">

                            {{-- FILE LAMA --}}
                            @php
                                $payment = \App\Models\Payment::where('student_id', $student->id)
                                    ->where('type', 'registration')
                                    ->first();
                            @endphp

                            @if($payment && $payment->proof_file)

                                @php
                                    $proofUrl = asset('storage/' . $payment->proof_file);
                                    $proofExt = strtolower(pathinfo($payment->proof_file, PATHINFO_EXTENSION));
                                @endphp

                                <div>

                                    <p class="mb-2 text-xs font-semibold text-slate-500">
                                        File Sebelumnya
                                    </p>

                                    {{-- IMAGE --}}
                                    @if(in_array($proofExt, ['jpg','jpeg','png']))

                                        <img src="{{ $proofUrl }}"
                                            onclick="openUploadPreview('image', '{{ $proofUrl }}')"
                                            class="object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    {{-- PDF --}}
                                    @elseif($proofExt === 'pdf')

                                        <div
                                            onclick="openUploadPreview('pdf', '{{ $proofUrl }}')"
                                            class="flex flex-col items-center justify-center w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                            <div class="text-4xl">
                                                📄
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            @endif

                            {{-- PREVIEW BARU --}}
                            <div id="preview_proof_wrapper"
                                class="hidden">

                                <p class="mb-2 text-xs font-semibold text-slate-500">
                                    Preview Baru
                                </p>

                                {{-- IMAGE --}}
                                <img id="preview_proof"
                                    class="hidden object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md"
                                    onclick="openUploadPreview('image', this.src)">

                                {{-- PDF --}}
                                <div id="preview_proof_pdf"
                                    onclick="openUploadPreview('pdf', this.dataset.src)"
                                    class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                    <div class="text-4xl">
                                        📄
                                    </div>

                                    <p class="mt-2 text-xs font-semibold text-red-500">
                                        PDF
                                    </p>

                                </div>

                            </div>

                        </div>

                    <input type="file" name="payment_proof" required
                           onchange="previewFile(event, 'proof')"
                           class="w-full p-2 border rounded-lg">

                    <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                    @error('payment_proof')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <button class="flex items-center gap-2 mt-4 shadow-sm btn-primary">
                    <iconify-icon
                        icon="solar:refresh-bold-duotone"
                        width="20">
                    </iconify-icon>

                    Daftar Ulang
                </button>

            </form>
        </div>

    </div>

    {{-- ================= UPLOAD PREVIEW MODAL ================= --}}
    <div id="uploadPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closeUploadPreview()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="uploadPreviewImage"
                class="hidden object-contain w-full max-h-[90vh] rounded-lg shadow-2xl">

            {{-- PDF --}}
            <iframe id="uploadPreviewPdf"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>
</x-app-layout>

<script>

    function previewFile(event, type) {

        const file = event.target.files[0];

        if (!file) return;

        const wrapper = document.getElementById(`preview_${type}_wrapper`);
        const image = document.getElementById(`preview_${type}`);
        const pdf = document.getElementById(`preview_${type}_pdf`);

        wrapper.classList.remove('hidden');

        const fileType = file.type;

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (fileType.startsWith('image/')) {

            image.src = URL.createObjectURL(file);
            image.classList.remove('hidden');

        }

        // PDF
        else if (fileType === 'application/pdf') {

            pdf.dataset.src = URL.createObjectURL(file);

            pdf.classList.remove('hidden');
            pdf.classList.add('flex');
        }
    }

    function openUploadPreview(type, src) {

        const modal = document.getElementById('uploadPreviewModal');

        const image = document.getElementById('uploadPreviewImage');
        const pdf = document.getElementById('uploadPreviewPdf');

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

    function closeUploadPreview() {

        const modal = document.getElementById('uploadPreviewModal');

        document.getElementById('uploadPreviewImage').src = '';
        document.getElementById('uploadPreviewPdf').src = '';

        modal.style.display = 'none';
    }

    // backdrop click
    document.getElementById('uploadPreviewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'uploadPreviewModal') {
                closeUploadPreview();
            }

        });

</script>