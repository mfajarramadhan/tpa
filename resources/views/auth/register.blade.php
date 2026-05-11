<x-guest-layout>
<div class="grid w-full min-h-screen grid-cols-1 overflow-hidden md:grid-cols-12 bg-gradient-to-b from-blue-900 to-blue-500">

    <!-- LEFT -->
    <div class="flex col-span-1 p-10 text-white md:py-8 md:col-span-7 md:px-32">
        <div class="flex flex-col justify-between w-full min-w-sm">
            <div>
                <h1 class="my-8 text-4xl font-bold leading-tight md:text-4xl text-indigo-50">
                    Selamat Datang di Sistem Informasi <span class="mt-2 md:block">TPA/DTA Al-Barokah</span>
                </h1>

                <p class="mb-2 text-lg">
                    Platform digital untuk mengelola pendaftaran, absensi, serta pembelajaran online secara real-time!
                </p>

                <div class="flex justify-around gap-5">
                    <img src="{{ asset('storage/logo/dta.png') }}"
                        class="object-contain md:w-64 md:h-64 w-52 h-52 my-9 transform-gpu hover:scale-105">

                    <div class="flex flex-col justify-center">
                        <p class="mb-2 text-lg">✅ Manajemen pendaftaran berbasis web</p>
                        <p class="mb-2 text-lg">✅ Pencatatan absensi secara digital</p>
                        <p class="mb-2 text-lg">✅ Pembelajaran online terintegrasi</p>
                    </div>
                </div>

                <p class="mb-1 text-base">Sudah punya akun?</p>

                <div class="flex items-center h-12">
                    <a class="px-4 py-2 font-semibold text-blue-900 bg-white border border-blue-500 rounded-lg hover:bg-gray-50" href="{{ route('login') }}">
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

                <h2 class="mb-8 text-4xl font-bold text-slate-600">Registrasi</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAME -->
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                        placeholder="Nama Lengkap"
                        class="w-full px-4 py-4 mb-4 text-lg border-b text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <x-input-error :messages="$errors->get('name')" class="mb-3"/>

                    <!-- PHONE -->
                    <input id="phone" name="phone" type="text"
                        value="{{ old('phone') }}"
                        placeholder="Nomor Telepon"
                        maxlength="13"
                        inputmode="numeric"
                        pattern="[0-9]{10,13}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full px-4 py-4 mb-4 text-lg border-b text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        required>

                    <x-input-error :messages="$errors->get('phone')" class="mb-3"/>

                    <!-- EMAIL -->
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                        placeholder="Email"
                        class="w-full px-4 py-4 mb-4 text-lg border-b text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <x-input-error :messages="$errors->get('email')" class="mb-3"/>

                    <!-- ADDRESS -->
                    <textarea name="address"
                        placeholder="Alamat"
                        class="w-full px-4 py-4 mb-4 text-lg border-b text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    <x-input-error :messages="$errors->get('address')" class="mb-3"/>

                    <!-- PASSWORD -->
                    <div class="relative mb-4">
                        <input id="password" name="password" type="password"
                            placeholder="Password"
                            class="w-full px-4 py-4 pr-12 text-lg border-b text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>

                        <button type="button"
                            onclick="togglePassword('password')"
                            class="absolute text-gray-500 -translate-y-1/2 right-3 top-1/2 hover:text-blue-500">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7
                                    -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mb-3"/>

                    <!-- CONFIRM PASSWORD -->
                    <div class="relative mb-8">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Konfirmasi Password"
                            class="w-full px-4 py-4 pr-12 text-lg border-b text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>

                        <button type="button"
                            onclick="togglePassword('password_confirmation')"
                            class="absolute text-gray-500 -translate-y-1/2 right-3 top-1/2 hover:text-blue-500">
                            <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mb-3"/>

                    <!-- BUTTON -->
                    <button type="submit"
                        class="w-full py-4 font-bold text-white transition rounded-full bg-gradient-to-t from-blue-900 to-blue-500 hover:-translate-y-1 hover:shadow-lg">
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
function togglePassword(id) {
    const p = document.getElementById(id);
    p.type = p.type === 'password' ? 'text' : 'password';
}
</script>