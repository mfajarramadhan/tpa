<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah User</h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        <form method="POST" action="{{ route('users.store') }}" class="p-5 bg-white rounded shadow">
            @csrf

            {{-- NAMA --}}
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>

            {{-- PHONE --}}
            <div class="mb-3">
                <label>Nomor Telepon</label>
                <input type="text" name="phone" 
                placeholder="08xxxxxxxxxx"
                maxlength="13"
                inputmode="numeric"
                pattern="[0-9]{10,13}"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                class="w-full p-2 border rounded" required>
            </div>

            {{-- ALAMAT --}}
           <div class="mb-3">
                <label>Alamat</label>

                <textarea name="address"
                        rows="3"
                        placeholder="Masukkan alamat lengkap..."
                        class="w-full p-2 text-sm border rounded-lg resize-none focus:ring focus:ring-blue-200"
                        required></textarea>
            </div>

            {{-- EMAIL --}}
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>

            {{-- PASSWORD --}}
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>

             {{-- CONFIRM PASSWORD --}}
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full p-2 border rounded" required>
            </div>

            {{-- ROLE --}}
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="w-full p-2 border rounded">

                    <option value="guru">Guru</option>
                    <option value="orang_tua">Orang Tua</option>
                    <option value="superadmin">Super Admin</option>

                </select>
            </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Simpan
            </button>

        </form>

    </div>
</x-app-layout>