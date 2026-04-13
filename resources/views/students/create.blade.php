<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah Anak</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">

            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label>Nama</label>
                    <input type="text" name="name" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label>NIK</label>
                    <input type="text" name="nik" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label>Jenis Kelamin</label>
                    <select name="gender" class="w-full p-2 border rounded">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Alamat</label>
                    <textarea name="address" class="w-full p-2 border rounded"></textarea>
                </div>

                <div class="mb-4">
                    <label>Upload KK</label>
                    <input type="file" name="kk_file">
                </div>

                <div class="mb-4">
                    <label>Upload Akta</label>
                    <input type="file" name="birth_certificate_file">
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan
                </button>

            </form>

        </div>
    </div>
</x-app-layout>