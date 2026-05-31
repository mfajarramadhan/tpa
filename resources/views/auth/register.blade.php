<x-guest-layout>
<div class="grid w-full min-h-screen grid-cols-1 overflow-hidden md:grid-cols-12 bg-[var(--primary)]">

    <!-- LEFT -->
    <div class="flex col-span-1 p-10 text-white md:py-8 md:col-span-7 md:px-32">
        <div class="flex flex-col justify-between w-full min-w-sm">
            <div>
                <h1 class="my-8 text-4xl font-bold leading-tight text-white md:text-4xl">
                    Selamat Datang di Sistem Informasi <span class="mt-2 md:block">TPA/DTA Al-Barokah</span>
                </h1>

                <p class="mb-2 text-lg">
                    Platform digital untuk mengelola pendaftaran, absensi, serta pembelajaran online secara real-time!
                </p>

                <div class="grid grid-cols-2 gap-4 my-8">

                    <!-- Logo -->
                    <div class="flex items-center justify-center">
                        <img
                            src="{{ asset('storage/logo/dta.png') }}"
                            alt="Logo DTA"
                            class="object-contain w-full max-w-[180px] md:max-w-[260px] transition-transform hover:scale-105">
                    </div>

                    <!-- Features -->
                    <div class="flex flex-col justify-center gap-4">

                        <div class="flex items-center">
                            <iconify-icon
                                icon="solar:shield-check-bold"
                                class="flex-shrink-0 mr-3 text-2xl text-blue-400">
                            </iconify-icon>
                            <span class="text-sm md:text-lg">
                                Pendaftaran berbasis web
                            </span>
                        </div>

                        <div class="flex items-center">
                            <iconify-icon
                                icon="solar:shield-check-bold"
                                class="flex-shrink-0 mr-3 text-2xl text-blue-400">
                            </iconify-icon>
                            <span class="text-sm md:text-lg">
                                Pencatatan absensi secara digital
                            </span>
                        </div>

                        <div class="flex items-center">
                            <iconify-icon
                                icon="solar:shield-check-bold"
                                class="flex-shrink-0 mr-3 text-2xl text-blue-400">
                            </iconify-icon>
                            <span class="text-sm md:text-lg">
                                Pembelajaran online terintegrasi
                            </span>
                        </div>

                    </div>

                </div>

                <p class="mb-1 text-base">Sudah punya akun?</p>

                <div class="flex items-center h-12">
                    <a 
                        href="{{ route('login') }}"
                        class="px-4 py-2 font-semibold text-[var(--primary)] bg-white border border-white rounded-lg hover:bg-gray-50">
                        Log In
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="relative col-span-1 px-6 md:px-0 md:col-span-5 flex md:rounded-tl-[44px] bg-white">
        <div class="absolute top-4 right-0 -left-4 h-full w-full rounded-tl-[44px] bg-white/50 hidden md:block"></div>

        <div class="z-10 w-full">
            <div class="max-w-sm p-4 mx-auto mt-6 bg-white sm:p-10 lg:max-w-lg xl:max-w-xl">

                <h2 class="mb-8 text-4xl font-bold text-[var(--primary)]">Registrasi</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAME -->
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        value="{{ old('name') }}"
                        placeholder="Nama Lengkap"
                        class="w-full px-4 py-4 mb-4 text-lg border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none" 
                        required>
                    <x-input-error :messages="$errors->get('name')" class="mb-3"/>

                    <!-- PHONE -->
                    <input 
                        id="phone" 
                        name="phone" 
                        type="text"
                        value="{{ old('phone') }}"
                        placeholder="Nomor Telepon"
                        maxlength="13"
                        inputmode="numeric"
                        pattern="[0-9]{10,13}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full px-4 py-4 mb-4 text-lg border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
                        required>
                    <x-input-error :messages="$errors->get('phone')" class="mb-3"/>

                    <!-- EMAIL -->
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email') }}"
                        placeholder="Email"
                        class="w-full px-4 py-4 mb-4 text-lg border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none" 
                        required>
                    <x-input-error :messages="$errors->get('email')" class="mb-3"/>

                    <!-- ADDRESS -->
                    <textarea 
                        name="address"
                        placeholder="Alamat"
                        class="w-full px-4 py-4 mb-4 text-lg border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mb-3"/>

                    <!-- PASSWORD -->
                    <div class="relative mb-4">
                        <input 
                            id="password" 
                            name="password" 
                            type="password"
                            placeholder="Password"
                            class="w-full px-4 py-4 pr-12 text-lg border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none" 
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
                    <x-input-error :messages="$errors->get('password')" class="mb-3"/>

                    <!-- CONFIRM PASSWORD -->
                    <div class="relative mb-8">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password"
                            placeholder="Konfirmasi Password"
                            class="w-full px-4 py-4 pr-12 text-lg border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none" 
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
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mb-3"/>

                    <!-- BUTTON -->
                    <button 
                        type="submit"
                        class="w-full py-4 font-bold text-white transition rounded-full bg-[var(--primary)] hover:bg-[var(--primary-dark)] hover:-translate-y-1 hover:shadow-lg">
                        Register
                    </button>

                    <p class="mt-4 text-xs text-center text-gray-500">
                        *Saya menyetujui syarat & ketentuan yang berlaku di TPA/DTA Al-Barokah
                    </p>

                </form>
            </div>
        </div>
    </div>

</div>
</x-guest-layout>

<script>
    function togglePassword(id, iconId) {
        const p = document.getElementById(id);
        const icon = document.getElementById(iconId);

        if (p.type === 'password') {
            p.type = 'text';
            icon.setAttribute('icon', 'solar:eye-closed-bold');
        } else {
            p.type = 'password';
            icon.setAttribute('icon', 'solar:eye-bold');
        }
    }
</script>