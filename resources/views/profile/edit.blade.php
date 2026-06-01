<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Profile</h2>
    </x-slot>

    <div class="py-6 md:py-0">

        {{-- Alert --}}
        <div class="relative">

            {{-- FLOATING ALERT WRAPPER --}}
            <div class="absolute top-0 left-0 z-50 w-full pointer-events-none">

                {{-- SUCCESS --}}
                @if(session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    @click.outside="show = false"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="pointer-events-auto flex items-center p-3 text-white rounded-xl shadow-md 
                        bg-gradient-to-t from-[var(--primary-dark)] to-[var(--primary)] 
                        bg-opacity-80 backdrop-blur-sm">

                    <div class="text-sm font-semibold ms-2">
                        {{ session('success') }}
                    </div>

                    <button @click="show = false"
                        class="flex items-center justify-center w-8 h-8 font-bold text-black transition rounded-md ms-auto bg-white/80 hover:bg-white">
                        ✕
                    </button>
                </div>
                @endif


                {{-- ERROR --}}
                @if(session('error'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    @click.outside="show = false"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="pointer-events-auto flex items-center p-3 text-white rounded-xl shadow-md 
                        bg-gradient-to-t from-[var(--danger)] to-red-400 
                        bg-opacity-80 backdrop-blur-sm">

                    <div class="text-sm font-semibold ms-2">
                        {{ session('error') }}
                    </div>

                    <button @click="show = false"
                        class="flex items-center justify-center w-8 h-8 font-bold text-black transition rounded-md ms-auto bg-white/80 hover:bg-white">
                        ✕
                    </button>
                </div>
                @endif

            </div>

        </div>
        
        {{-- Previous --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
            class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>
        </div>

        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                {{-- HEADER --}}
                <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:user-id-bold-duotone"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-[var(--text-main)]">
                            Edit Profile
                        </h2>

                        <p class="text-sm text-[var(--text-tertiary)]">
                            Perbarui data akun anda
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
                        value="{{ old('name', $user->name) }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-[var(--primary-light)]">

                    @error('name')
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
                        value="{{ old('email', $user->email) }}"
                        class="w-full p-2 border rounded-lg focus:ring focus:ring-[var(--primary-light)]">

                    @error('email')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if (!$user->student)

                    {{-- ALAMAT --}}
                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-semibold">
                            Alamat
                        </label>

                        <input type="text"
                            name="address"
                            value="{{ old('address', $user->address) }}"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-[var(--primary-light)]">

                        @error('address')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                @endif

                <hr class="my-6 border-custom">

                {{-- PASSWORD HEADER --}}
                <div class="flex items-center gap-3 mb-4">

                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:lock-password-bold-duotone"
                            class="text-lg text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h3 class="text-base font-bold text-[var(--text-main)]">
                            Ganti Password
                        </h3>

                        <p class="text-sm text-[var(--text-tertiary)]">
                            Kosongkan jika tidak ingin mengganti password
                        </p>

                    </div>

                </div>

                <div x-data="{ showPassword: false, showConfirm: false }">

                {{-- PASSWORD --}}
                <div class="relative mb-4">
                    <input
                        :type="showConfirm ? 'text' : 'password'"
                        name="password_confirmation"
                        placeholder="Konfirmasi password"
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        class="w-full p-2 pr-10 border rounded-lg focus:ring focus:ring-[var(--primary-light)]">

                    <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 flex items-center text-gray-500 right-3 hover:text-[var(--primary)]">

                        <iconify-icon
                            :icon="showPassword ? 'solar:eye-closed-bold' : 'solar:eye-bold'"
                            class="text-2xl">
                        </iconify-icon>

                    </button>
                    
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- CONFIRM PASSWORD --}}
                <div class="relative mb-4">
                    <input
                        :type="showConfirm ? 'text' : 'password'"
                        name="password_confirmation"
                        placeholder="Konfirmasi password"
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        class="w-full p-2 pr-10 border rounded-lg focus:ring focus:ring-[var(--primary-light)]">

                    <button type="button"
                            @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 flex items-center text-gray-500 right-3 hover:text-[var(--primary)]">

                        <iconify-icon
                            :icon="showConfirm ? 'solar:eye-closed-bold' : 'solar:eye-bold'"
                            class="text-2xl">
                        </iconify-icon>

                    </button>
                    
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


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
</x-app-layout>