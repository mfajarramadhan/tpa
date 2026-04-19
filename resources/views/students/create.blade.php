<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah Anak</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white shadow-sm rounded-2xl">

            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIK --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                           maxlength="16" inputmode="numeric"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Tanggal Lahir</label>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date') }}"
                           max="{{ now()->subYears(3)->format('Y-m-d') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
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

                {{-- SEKOLAH --}}
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

                {{-- INFO BIAYA --}}
                <div class="p-3 mt-4 text-sm bg-yellow-100 rounded-lg">
                    <strong>Biaya Pendaftaran:</strong>
                    Rp {{ number_format($fee->registration_fee) }}
                </div>

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

                {{-- KK --}}
                <div class="mt-4">
                    <label class="block mb-1 text-sm font-semibold">Upload KK</label>

                    <img id="preview_kk" class="hidden w-32 mb-2 border rounded-lg"/>

                    <input type="file" name="kk_file"
                           onchange="previewImage(event, 'preview_kk')"
                           class="w-full p-2 border rounded-lg">

                    <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                    @error('kk_file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- AKTA --}}
                <div class="mt-4">
                    <label class="block mb-1 text-sm font-semibold">Upload Akta</label>

                    <img id="preview_akta" class="hidden w-32 mb-2 border rounded-lg"/>

                    <input type="file" name="birth_certificate_file"
                           onchange="previewImage(event, 'preview_akta')"
                           class="w-full p-2 border rounded-lg">

                    <p class="text-xs text-gray-500">Maksimal ukuran file 2MB</p>

                    @error('birth_certificate_file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="mt-6">
                    <button class="px-5 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- SCRIPT PREVIEW --}}
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
</x-app-layout>