<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Dashboard Guru
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
                        Berikut ringkasan aktivitas pembelajaran TPA/DTA Al-Barokah secara real-time!
                    </p>
                </div>
            </div>

            {{-- Statistic Cards --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 sm:gap-5">

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

                {{-- Total Materi --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Materi</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:book-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $materials }}
                    </div>
                </div>

                {{-- Total Tugas --}}
                <div class="stat-card">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Total Tugas</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon
                                icon="solar:clipboard-list-bold-duotone"
                                width="18">
                            </iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $tasks }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>