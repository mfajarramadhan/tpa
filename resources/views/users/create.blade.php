<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tambah User</h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- BUTTON --}}
        <div class="mb-6">

            <a href="{{ route('users.index') }}"
               class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

        </div>

        {{-- CARD --}}
        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST" action="{{ route('users.store') }}">
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
                            Tambah User
                        </h2>

                        <p class="text-sm text-[var(--text-tertiary)]">
                            Daftarkan user baru
                        </p>

                    </div>

                </div>

                {{-- NAMA --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Nama
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                           required>

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PHONE --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Nomor Telepon
                    </label>

                    <input type="text"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="08xxxxxxxxxx"
                           maxlength="13"
                           inputmode="numeric"
                           pattern="[0-9]{10,13}"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                           required>

                    @error('phone')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ALAMAT --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Alamat
                    </label>

                    <textarea name="address"
                              rows="3"
                              placeholder="Masukkan alamat lengkap..."
                              class="w-full p-2 text-sm border rounded-lg resize-none focus:ring focus:ring-blue-200"
                              required>{{ old('address') }}</textarea>

                    @error('address')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200"
                           required>

                    @error('email')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Password
                    </label>

                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="w-full p-2 pr-10 border rounded-lg focus:ring focus:ring-[var(--primary-light)]"
                            required>

                        <button 
                            type="button"
                            onclick="togglePassword('password', 'eyeIcon')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[var(--primary)]">

                            <iconify-icon
                                id="eyeIcon"
                                icon="solar:eye-bold"
                                class="text-2xl">
                            </iconify-icon>

                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Konfirmasi Password
                    </label>

                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="w-full p-2 pr-10 border rounded-lg focus:ring focus:ring-[var(--primary-light)]"
                            required>

                        <button 
                            type="button"
                            onclick="togglePassword('password_confirmation', 'eyeIconConfirm')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[var(--primary)]">

                            <iconify-icon
                                id="eyeIconConfirm"
                                icon="solar:eye-bold"
                                class="text-2xl">
                            </iconify-icon>

                        </button>
                    </div>

                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ROLE --}}
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">
                        Role
                    </label>

                    <select name="role"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">

                        <option value="guru"
                            {{ old('role') == 'guru' ? 'selected' : '' }}>
                            Guru
                        </option>

                        <option value="orang_tua"
                            {{ old('role') == 'orang_tua' ? 'selected' : '' }}>
                            Orang Tua
                        </option>

                        <option value="superadmin"
                            {{ old('role') == 'superadmin' ? 'selected' : '' }}>
                            Super Admin
                        </option>

                    </select>

                    @error('role')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="mt-6">
                    <button class="shadow-sm btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>

<script>
function togglePassword(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('icon', 'solar:eye-closed-bold');
    } else {
        input.type = 'password';
        icon.setAttribute('icon', 'solar:eye-bold');
    }
}
</script>