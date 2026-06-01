<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Pengaturan Biaya</h2>
    </x-slot>

    <div class="flex flex-col py-6 overflow-hidden shadow-sm card-panel md:py-0">

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

        {{-- HEADER --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-custom">

            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                <iconify-icon
                    icon="solar:wallet-money-bold-duotone"
                    class="text-xl text-[var(--primary)]">
                </iconify-icon>

            </div>

            <div>

                <h2 class="text-xl font-bold text-[var(--text-main)]">
                    Biaya Default
                </h2>

            </div>

        </div>

        <!-- Form -->
        <div class="p-5 space-y-4">

            <form method="POST"
                action="{{ route('fees.update') }}"
                onsubmit="confirmAction(
                    event,
                    'Update Informasi Biaya?',
                    'Perubahan informasi biaya akan disimpan',
                    'Ya, Update',
                    'question'
                )">
                @csrf

                <!-- Biaya Pendaftaran -->
                <div>
                    <label class="font-semibold text-[var(--text-main)] mb-1 block">
                        Biaya Pendaftaran
                    </label>
                    <input type="number" name="registration_fee"
                        value="{{ $fee->registration_fee }}"
                        placeholder="Contoh: Rp. 100.000"
                        class="input-solid w-full rounded-xl py-2.5 border-[var(--border)] bg-[var(--surface)]">

                    @if ($errors->any())
                        <div class="text-[var(--danger)] text-xs mt-1">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>

                <!-- Iuran Bulanan -->
                <div>
                    <label class="font-semibold text-[var(--text-main)] mb-1 mt-2 block">
                        Iuran Bulanan
                    </label>
                    <input type="number" name="monthly_fee"
                        value="{{ $fee->monthly_fee }}"
                        placeholder="Contoh: Rp. 100.000"
                        class="input-solid w-full rounded-xl py-2.5 border-[var(--border)] bg-[var(--surface)]">
                </div>

                <!-- Info -->
                <p class="text-xs text-[var(--text-tertiary)] italic mb-1 mt-1">
                    <span class="font-bold">Catatan:</span> Perubahan biaya iuran bulanan mulai berlaku di bulan berikutnya
                </p>

                <hr class="my-6 border-custom">

                {{-- HEADER BANK --}}
                <div class="flex items-center gap-3 px-2 mb-6">

                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                        <iconify-icon
                            icon="solar:wallet-money-bold-duotone"
                            class="text-xl text-[var(--primary)]">
                        </iconify-icon>

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-[var(--text-main)]">
                            Informasi Bank
                        </h2>

                    </div>

                </div>

                <!-- Nama Bank -->
                <div>
                    <label class="font-semibold text-[var(--text-main)] mb-1 mt-2 block">
                        Nama Bank
                    </label>

                    <input type="text"
                        name="bank_name"
                        value="{{ old('bank_name', $fee->bank_name) }}"
                        placeholder="Contoh: BCA"
                        class="input-solid w-full rounded-xl py-2.5 border-[var(--border)] bg-[var(--surface)]">
                </div>

                <!-- Nama Rekening -->
                <div>
                    <label class="font-semibold text-[var(--text-main)] mb-1 mt-2 block">
                        Nama Rekening
                    </label>

                    <input type="text"
                        name="account_name"
                        value="{{ old('account_name', $fee->account_name) }}"
                        placeholder="Contoh: Yayasan Al-Barokah"
                        class="input-solid w-full rounded-xl py-2.5 border-[var(--border)] bg-[var(--surface)]">
                </div>

                <!-- Nomor Rekening -->
                <div>
                    <label class="font-semibold text-[var(--text-main)] mb-1 mt-2 block">
                        Nomor Rekening
                    </label>

                    <input type="text"
                        name="account_number"
                        value="{{ old('account_number', $fee->account_number) }}"
                        placeholder="Contoh: 1234567890"
                        class="input-solid w-full rounded-xl py-2.5 border-[var(--border)] bg-[var(--surface)]">
                </div>

                <!-- Button -->
                <button type="submit"
                    class="flex items-center gap-2 mt-4 shadow-sm btn-primary">
                    <iconify-icon icon="solar:diskette-bold-duotone" width="20"></iconify-icon>
                    Simpan Biaya
                </button>

            </form>

        </div>

        <!-- Riwayat -->
        <div class="p-5 border-t border-[var(--border-light)]">
            <h3 class="mb-4 font-semibold text-[var(--text-main)]">
                Riwayat Perubahan Biaya
            </h3>

            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Pendaftaran</th>
                            <th>Iuran Bulanan</th>
                            <th class="!text-center">Bank & Rekening</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($logs as $log)
                            <tr>

                                <td class="font-semibold text-[var(--text-main)]">
                                    {{ $log->user->name }}
                                </td>

                                {{-- REGISTRASI --}}
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[var(--danger)] font-mono text-xs">
                                            {{ number_format($log->old_registration_fee) }}
                                        </span>
                                        <iconify-icon icon="solar:arrow-right-bold-duotone" class="text-[var(--text-tertiary)]"></iconify-icon>
                                        <span class="text-[var(--success)] font-mono text-xs">
                                            {{ number_format($log->new_registration_fee) }}
                                        </span>
                                    </div>
                                </td>

                                {{-- IURAN BULANAN --}}
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[var(--danger)] font-mono text-xs">
                                            {{ number_format($log->old_monthly_fee) }}
                                        </span>
                                        <iconify-icon icon="solar:arrow-right-bold-duotone" class="text-[var(--text-tertiary)]"></iconify-icon>
                                        <span class="text-[var(--success)] font-mono text-xs">
                                            {{ number_format($log->new_monthly_fee) }}
                                        </span>
                                    </div>
                                </td>

                                {{-- BANK --}}
                                <td class="min-w-[280px]">

                                    <div class="flex items-center gap-3">

                                        {{-- OLD --}}
                                        <div class="flex-1 p-2 rounded-xl bg-[var(--danger-light)]/40 border border-[var(--danger)]/10">

                                            <p class="text-xs font-semibold text-[var(--danger)]">
                                                {{ $log->old_bank_name ?? '-' }}
                                            </p>

                                            <p class="text-[11px] text-[var(--text-secondary)]">
                                                {{ $log->old_account_name ?? '-' }}
                                            </p>

                                            <p class="font-mono text-xs text-[var(--danger)] mt-1">
                                                {{ $log->old_account_number ?? '-' }}
                                            </p>

                                        </div>

                                        {{-- ARROW --}}
                                        <div class="flex-shrink-0">

                                            <iconify-icon
                                                icon="solar:arrow-right-bold-duotone"
                                                class="text-xl text-[var(--text-tertiary)]">
                                            </iconify-icon>

                                        </div>

                                        {{-- NEW --}}
                                        <div class="flex-1 p-2 rounded-xl bg-[var(--success-light)]/40 border border-[var(--success)]/10">

                                            <p class="text-xs font-semibold text-[var(--success)]">
                                                {{ $log->new_bank_name ?? '-' }}
                                            </p>

                                            <p class="text-[11px] text-[var(--text-secondary)]">
                                                {{ $log->new_account_name ?? '-' }}
                                            </p>

                                            <p class="font-mono text-xs text-[var(--success)] mt-1">
                                                {{ $log->new_account_number ?? '-' }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="font-mono text-xs text-[var(--text-tertiary)]">
                                    {{ $log->created_at->format('d M Y | H:i') }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
                {{-- Pagination --}}
                @if($logs->hasPages())

                    <div class="flex items-center justify-between p-5 border-t border-[var(--border-light)] bg-[var(--surface)]">

                        {{-- Info --}}
                        <div class="text-sm font-medium text-[var(--text-tertiary)]">

                            Menampilkan
                            {{ $logs->firstItem() ?? 0 }}
                            -
                            {{ $logs->lastItem() ?? 0 }}

                            dari

                            {{ $logs->total() }}

                            data

                        </div>

                        {{-- Button --}}
                        <div class="flex gap-2">

                            {{-- Prev --}}
                            @if($logs->onFirstPage())

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    &laquo; Prev
                                </span>

                            @else

                                <a href="{{ $logs->previousPageUrl() }}"
                                class="px-3 py-1.5 text-sm border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    &laquo; Prev

                                </a>

                            @endif

                            {{-- Number --}}
                            @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)

                                @if($page == $logs->currentPage())

                                    <span class="px-3.5 py-1.5 text-sm shadow-md btn-primary">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $url }}"
                                    class="px-3.5 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                        {{ $page }}

                                    </a>

                                @endif

                            @endforeach

                            {{-- Next --}}
                            @if($logs->hasMorePages())

                                <a href="{{ $logs->nextPageUrl() }}"
                                class="px-3 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    Next &raquo;

                                </a>

                            @else

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    Next &raquo;
                                </span>

                            @endif

                        </div>

                    </div>

                @endif
            </div>
        </div>

    </div>
</x-app-layout>