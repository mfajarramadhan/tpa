<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Dashboard Superadmin
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

            {{-- Welcome Section --}}
            <div class="flex flex-col items-start justify-between gap-4 mb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 class="flex items-center gap-2 m-0">
                        Selamat Datang, {{ Auth::user()->name }}

                        <iconify-icon
                            icon="solar:hand-stars-bold-duotone"
                            class="text-[#FFC107]">
                        </iconify-icon>
                    </h1>

                    <p class="mt-1 font-medium text-body text-[var(--text-tertiary)]">
                        Berikut ringkasan informasi TPA/DTA Al-Barokah secara real-time!
                    </p>
                </div>
            </div>

            {{-- Statistic Cards --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-3 sm:gap-5">

                {{-- Total Guru --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Guru</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:user-check-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $totalTeachers }}
                    </div>
                </div>

                {{-- Total Orang Tua --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Orang Tua</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:user-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $totalParents }}
                    </div>
                </div>

                {{-- Total Siswa --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Siswa</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:people-nearby-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $totalStudents }}
                    </div>
                </div>

                {{-- Pending Iuran --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Pending Iuran</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:wallet-money-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $pendingPayments }}
                    </div>
                </div>

                {{-- Pending Approval --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Pending Approval</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:clock-circle-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $pendingStudents }}
                    </div>
                </div>

                {{-- Pendaftaran Ditolak --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Pendaftaran Ditolak</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:close-circle-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $rejectedStudents }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>