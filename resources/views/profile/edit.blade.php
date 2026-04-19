<x-app-layout>

    <div class="max-w-xl p-6 mx-auto mt-6 bg-white rounded shadow">

        <h2 class="mb-4 text-lg font-semibold">Profile Saya</h2>

        @if(session('success'))
            <div class="p-2 mb-3 text-sm text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            {{-- NAMA --}}
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full p-2 border rounded">
            </div>

            {{-- EMAIL --}}
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full p-2 border rounded">
            </div>

            @if (!$user->student)
            {{-- ALAMAT --}}
            <div class="mb-3">
                <label>Alamat</label>
                <input type="text" name="address"
                value="{{ old('address', $user->address) }}"
                class="w-full p-2 border rounded">
            </div>
            @endif

            <hr class="my-4">

            <h3 class="mb-2 text-sm font-semibold">Ganti Password</h3>

            <div x-data="{ showPassword: false, showConfirm: false }">

            {{-- PASSWORD --}}
            <div class="relative mb-3">
                <input :type="showPassword ? 'text' : 'password'"
                    name="password"
                    placeholder="Password baru"
                    class="w-full p-2 pr-10 border rounded-lg">

                {{-- ICON --}}
                <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute text-gray-500 -translate-y-1/2 right-3 top-1/2 hover:text-gray-700">
                    👁️
                </button>
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="relative mb-3">
                <input :type="showConfirm ? 'text' : 'password'"
                    name="password_confirmation"
                    placeholder="Konfirmasi password"
                    class="w-full p-2 pr-10 border rounded-lg">

                {{-- ICON --}}
                <button type="button"
                        @click="showConfirm = !showConfirm"
                        class="absolute text-gray-500 -translate-y-1/2 right-3 top-1/2 hover:text-gray-700">
                    👁️
                </button>
            </div>

            <p class="mb-3 text-xs text-gray-500">
                Kosongkan jika tidak ingin mengganti password
            </p>

        </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Simpan
            </button>

        </form>

    </div>

</x-app-layout>