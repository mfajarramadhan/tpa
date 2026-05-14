<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Superadmin</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

        <!-- Welcome Section -->
        <div class="flex flex-col items-start justify-between gap-4 mb-8 sm:flex-row sm:items-center">
            <div>
                <h1 class="flex items-center gap-2 m-0">Selamat Datang, {{ Auth::user()->name }} <iconify-icon icon="solar:hand-stars-bold-duotone" class="text-[#FFC107]"></iconify-icon></h1>
                <p class="text-body mt-1 font-medium text-[var(--text-tertiary)]">Berikut ringkasan informasi TPA/DTA Al-Barokah secara real-time!</p>
            </div>
            {{-- <button class="shadow-sm btn-primary">
                <iconify-icon icon="solar:printer-bold-duotone" width="20"></iconify-icon> Cetak Ringkasan
            </button> --}}
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-5 sm:gap-5">

            <!-- Total Siswa -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Total Siswa</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                        <iconify-icon icon="solar:people-nearby-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data">{{ $totalStudents }}</div>
                </div>
            </div>

            <!-- Total Guru -->
            <div class="stat-card" style="border-left-color: var(--info);">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Total Guru</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--info-light)] text-[var(--info)]">
                        <iconify-icon icon="solar:user-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data text-[var(--info)]">{{ $totalTeachers }}</div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="stat-card" style="border-left-color: var(--warning-dark);">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Pending Approval</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--warning-light)] text-[var(--warning-dark)]">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data text-[var(--warning-dark)]">{{ $pendingStudents }}</div>
                </div>
            </div>

            <!-- Pending Iuran -->
            <div class="stat-card" style="border-left-color: var(--danger);">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Pending Iuran</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--danger-light)] text-[var(--danger)]">
                        <iconify-icon icon="solar:wallet-money-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data text-[var(--danger)]">{{ $pendingPayments }}</div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="stat-card" style="border-left-color: var(--purple);">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Ditolak</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--purple-light)] text-[var(--purple)]">
                        <iconify-icon icon="solar:close-circle-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data text-[var(--purple)]">{{ $rejectedStudents }}</div>
                </div>
            </div>

        </div>
        </div>
    </div>
</x-app-layout>