<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah Anak</h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BACK BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('students.index') }}"
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
                action="{{ route('students.store') }}"
                enctype="multipart/form-data"
                onsubmit="confirmAction(
                    event,
                    'Tambah Anak?',
                    'Data pendaftaran akan diverifikasi oleh admin',
                    'Ya, Tambah',
                    'question'
                )">
                
                @csrf

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:user-plus-bold-duotone"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-[var(--text-main)]">
                            Tambah Anak
                        </h2>

                        <p class="text-sm text-[var(--text-tertiary)]">
                            Daftarkan anak anda sebagai siswa
                        </p>

                    </div>

                </div>

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NISN --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">NISN (Nomor Induk Siswa Nasional)</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}"
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
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Tanggal Lahir</label>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date') }}"
                           max="{{ now()->subYears(8)->format('Y-m-d') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    <!-- Info -->
                    <p class="mb-2 text-xs text-gray-500">Umur anak minimal 8 tahun!</p>

                    @error('birth_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- GENDER --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Jenis Kelamin</label>
                    <select name="gender"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ASAL SEKOLAH --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Sekolah Asal</label>
                    <input type="text" name="school_origin"
                           value="{{ old('school_origin') }}"
                           placeholder="Contoh: SDN Klari"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    @error('school_origin')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KELAS DI SEKOLAH --}}
                <div class="mb-4">

                    <label class="block mb-1 text-sm font-semibold">
                        Tingkat Kelas di Sekolah
                    </label>

                    <select name="school_grade"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                        <option value="">
                            Pilih Kelas
                        </option>

                        <option value="1 SD" {{ old('school_grade') == '1 SD' ? 'selected' : '' }}>
                            1 SD
                        </option>

                        <option value="2 SD" {{ old('school_grade') == '2 SD' ? 'selected' : '' }}>
                            2 SD
                        </option>

                        <option value="3 SD" {{ old('school_grade') == '3 SD' ? 'selected' : '' }}>
                            3 SD
                        </option>

                        <option value="4 SD" {{ old('school_grade') == '4 SD' ? 'selected' : '' }}>
                            4 SD
                        </option>

                        <option value="5 SD" {{ old('school_grade') == '5 SD' ? 'selected' : '' }}>
                            5 SD
                        </option>

                        <option value="6 SD" {{ old('school_grade') == '6 SD' ? 'selected' : '' }}>
                            6 SD
                        </option>

                    </select>

                    @error('school_grade')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- KK --}}
                <div class="mt-4">
                    <label class="block mb-1 text-sm font-semibold">Upload KK</label>

                    <div class="mb-3">

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
                    <label class="block mb-1 text-sm font-semibold">Upload Akta</label>

                    <div class="mb-3">

                        <div id="preview_akta_wrapper"
                            class="hidden">

                            <img id="preview_akta"
                                class="object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md"
                                onclick="openUploadPreview('image', this.src)">

                            <div id="preview_akta_pdf"
                                onclick="openUploadPreview('pdf', this.dataset.src)"
                                class="flex-col items-center justify-center hidden w-32 h-32 transition bg-red-100 border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md">

                                <div class="text-4xl">📄</div>

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
                <div class="p-3 mt-4 border shadow-sm rounded-2xl border-yellow-300/40 bg-yellow-500/10">

                    <p class="text-sm text-[var(--text-tertiary)]">
                        Biaya Pendaftaran:
                    </p>

                    <p class="text-lg font-bold text-yellow-700">
                        Rp {{ number_format($fee->registration_fee) }}
                    </p>

                </div>

                {{-- BUKTI PEMBAYARAN --}}
                <div class="mt-4">
                    <label class="block mb-1 text-sm font-semibold">Bukti Pembayaran</label>

                    <div class="mb-3">

                        <div id="preview_proof_wrapper"
                            class="hidden">

                            {{-- IMAGE --}}
                            <img id="preview_proof"
                                class="object-cover w-32 h-32 transition border rounded-lg cursor-pointer hover:scale-105 hover:shadow-md"
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

                    <input type="file" name="proof_file" required
                           onchange="previewFile(event, 'proof')"
                           class="w-full p-2 border rounded-lg">

                    <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                    @error('proof_file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="mt-6">
                    <button type="submit"
                            class="shadow-sm btn-primary">
                        Simpan
                    </button>
                </div>

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