<x-guest-layout>
<div class="grid w-full min-h-screen grid-cols-1 overflow-hidden md:grid-cols-12 bg-gradient-to-b from-indigo-900 to-indigo-600">

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
                    <!-- Image -->
                    <img
                        src="{{ asset('storage/logo/dta.png') }}"
                        alt="PT Asteria"
                        class="object-contain md:w-64 md:h-64 w-52 h-52 my-9 transition-transform transform-gpu hover:scale-[105%]">

                    <div class="flex flex-col justify-center">
                        <p class="mb-2 text-lg">✅ Manajemen pendaftaran berbasis web</p>
                        <p class="mb-2 text-lg">✅ Pencatatan absensi secara digital</p>
                        <p class="mb-2 text-lg">✅ Pembelajaran online terintegrasi</p>
                    </div>
                </div>
                    
                <p class="mb-1 text-base">Belum punya akun?</p>

                <div class="flex items-center h-12">
                    <a class="px-4 py-2 font-semibold text-indigo-900 bg-white border border-indigo-500 rounded-lg hover:bg-gray-50" href="{{ route('register') }}">
                        Registrasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section -->
   <div class="relative z-3 col-span-1 px-6 md:px-0 md:col-span-5 flex md:rounded-tl-[44px] bg-white">
        <div class="absolute top-4 right-0 -left-4 z-2 h-full w-full rounded-tl-[44px] bg-white/50 hidden md:block"></div>

        <div class="z-10 w-full">
            <div class="max-w-sm p-4 mx-auto mt-6 bg-white md:mt-5 z-4 sm:p-10 lg:max-w-lg xl:max-w-xl">

                <h2 class="mb-10 text-4xl font-bold text-slate-600">Log In</h2>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- LOGIN (EMAIL / PHONE) -->
                    <div class="mb-6">
                        <input
                            id="login"
                            name="login"
                            type="text"
                            autofocus
                            value="{{ old('login') }}"
                            placeholder="Email atau Nomor Telepon"
                            maxlength="100"
                            inputmode="text"
                            oninput="
                                if(this.value.startsWith('08')){
                                    this.value = this.value.replace(/[^0-9]/g, '');
                                }
                            "
                            class="w-full px-4 py-5 text-lg font-medium border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required autofocus>
                            
                            <x-input-error :messages="$errors->get('login')" class="mt-1" />
                    </div>


                    <!-- Password -->
                    <div class="relative mb-6">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Password"
                            class="w-full px-4 py-5 pr-12 text-lg font-medium border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required>

                        <button type="button"
                            onclick="togglePassword()"
                            class="absolute text-gray-500 -translate-y-1/2 right-3 top-1/2 hover:text-indigo-500">
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

                    <x-input-error :messages="$errors->get('password')" class="mb-4" />

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-10">
                        <label class="flex items-center space-x-2">
                            <input id="remember_me" name="remember" type="checkbox" class="text-indigo-500 form-checkbox">
                            <span class="font-medium text-gray-600">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                            class="text-lg font-medium text-indigo-500 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full px-8 py-4 mb-6 font-bold text-white transition-transform rounded-full transform-gpu bg-gradient-to-t from-indigo-900 to-indigo-500 hover:-translate-y-1 hover:shadow-lg">
                        Log In
                    </button>
                </form>

                <!-- OR -->
                <div class="flex items-center justify-center mb-6">
                    <span class="w-1/5 border-b border-white lg:w-1/4"></span>
                    <span class="mx-2 text-xs text-gray-400">Atau</span>
                    <span class="w-1/5 border-b border-white lg:w-1/4"></span>
                </div>

                <!-- Social -->
                <div>
                    <button class="flex items-center justify-center w-full py-3 font-medium border border-gray-300 rounded-lg text-slate-700 hover:bg-gray-50">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 mr-3" alt="Google" />
                        Lanjutkan dengan Google
                    </button>
                </div>

                <p class="mt-12 text-sm text-center text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-indigo-500 hover:underline">Registrasi</a>
                </p>

            </div>
        </div>
    </div>

</div>
</x-guest-layout>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19
                   c-4.478 0-8.268-2.943-9.543-7
                   a9.956 9.956 0 012.042-3.368M6.223 6.223
                   A9.956 9.956 0 0112 5c4.478 0 8.268 2.943
                   9.543 7a9.978 9.978 0 01-4.132 5.411M15 12
                   a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18" />
        `;
    } else {
        password.type = "password";
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.478 0 8.268 2.943 9.542 7
                   -1.274 4.057-5.064 7-9.542 7
                   -4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>