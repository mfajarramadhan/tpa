<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Siswa</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">
        <!-- Welcome Section -->
        <div class="flex flex-col items-start justify-between gap-4 mb-8 sm:flex-row sm:items-center">
            <div>
                <h1 class="flex items-center gap-2 m-0">Selamat Datang, {{ Auth::user()->name }} - Kelas {{ Auth::user()->student->classroom->name }} <iconify-icon icon="solar:hand-stars-bold-duotone" class="text-[#FFC107]"></iconify-icon></h1>
                <p class="text-body mt-1 font-medium text-[var(--text-tertiary)]">Tetap semangat belajar dan jangan lupa kumpulkan tugas tepat waktu!</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-4 sm:gap-5">

            <!-- Total Materi -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Total Materi</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                        <iconify-icon icon="solar:people-nearby-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data">{{ $totalMaterials }}</div>
                </div>
            </div>

            <!-- Total Tugas -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-caption">Total Tugas</span>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-[var(--primary-light)] text-[var(--primary)]">
                        <iconify-icon icon="solar:people-nearby-bold-duotone" width="18"></iconify-icon>
                    </div>
                </div>
                <div>
                    <div class="text-data">{{ $totalTasks }}</div>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>