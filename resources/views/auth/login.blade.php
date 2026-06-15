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
                        
                    <p class="mb-1 text-base">Belum punya akun?</p>

                    <div class="flex items-center h-12">
                        <a 
                            href="{{ route('register') }}"
                            class="px-4 py-2 font-semibold text-[var(--primary)] bg-white border border-white rounded-lg hover:bg-gray-50">
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
                                class="w-full px-4 py-5 text-lg font-medium border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
                                required>
                                
                            <x-input-error :messages="$errors->get('login')" class="mt-1" />
                        </div>

                        <!-- Password -->
                        <div class="relative mb-6">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Password"
                                class="w-full px-4 py-5 pr-12 text-lg font-medium border-b border-gray-300 text-slate-700 focus:ring-2 focus:ring-[var(--primary)] focus:outline-none"
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

                        <x-input-error :messages="$errors->get('password')" class="mb-4" />

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between mb-10">
                            <label class="flex items-center space-x-2">
                                <input 
                                    id="remember_me" 
                                    name="remember" 
                                    type="checkbox" 
                                    class="text-[var(--primary)] form-checkbox">
                                <span class="font-medium text-gray-600">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a 
                                    href="{{ route('password.request') }}"
                                    class="text-lg font-medium text-[var(--primary)] hover:underline">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Button -->
                        <button 
                            type="submit"
                            class="w-full px-8 py-4 mb-6 font-bold text-white transition-transform rounded-full transform-gpu bg-[var(--primary)] hover:bg-[var(--primary-dark)] hover:-translate-y-1 hover:shadow-lg">
                            Log In
                        </button>
                    </form>

                    <!-- OR -->
                    <div class="flex items-center justify-center mb-6">
                        <span class="w-1/5 border-b border-gray-200 lg:w-1/4"></span>
                        <span class="mx-2 text-xs text-gray-400">Atau</span>
                        <span class="w-1/5 border-b border-gray-200 lg:w-1/4"></span>
                    </div>

                    <!-- Social -->
                    <div>
                        <button class="flex items-center justify-center w-full py-3 font-medium border border-gray-300 rounded-lg text-slate-700 hover:bg-gray-50">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 mr-3" alt="Google" />
                            Lanjutkan dengan Google
                        </button>
                    </div>

                    <p class="mt-2 text-sm text-center text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-[var(--primary)] hover:underline">Registrasi</a>
                    </p>

                </div>
            </div>
        </div>

    </div>
</x-guest-layout>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.setAttribute("icon", "solar:eye-closed-bold");
        } else {
            input.type = "password";
            icon.setAttribute("icon", "solar:eye-bold");
        }
    }
</script>