<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Pengaturan Biaya</h2>
    </x-slot>

    <div class="flex flex-col py-6 overflow-hidden shadow-sm card-panel md:py-0">

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
                    'Update Biaya?',
                    'Perubahan biaya akan disimpan',
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
                        class="input-solid w-full rounded-xl py-2.5 border-[var(--border)] bg-[var(--surface)]">
                </div>

                <!-- Info -->
                <p class="text-xs text-[var(--text-tertiary)] italic mb-1 mt-1">
                    <span class="font-bold">Catatan:</span> Perubahan biaya iuran bulanan mulai berlaku di bulan berikutnya
                </p>

                <!-- Button -->
                <button type="submit"
                    class="flex items-center gap-2 mt-2 shadow-sm btn-primary">
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
                            <th>Iuran</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($logs as $log)
                            <tr>

                                <td class="font-semibold text-[var(--text-main)]">
                                    {{ $log->user->name }}
                                </td>

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

                                <td class="font-mono text-xs text-[var(--text-tertiary)]">
                                    {{ $log->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</x-app-layout>