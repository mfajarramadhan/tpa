<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kenaikan Kelas</h2>
    </x-slot>

    <div class="py-6 md:py-0">  
        <div class="mx-auto max-w-7xl">
            {{-- WARNING --}}
            <div class="p-4 mb-6 border rounded-2xl bg-[var(--warning-light)] border-[var(--warning)]">

                <div class="flex items-start gap-3">

                    <iconify-icon
                        icon="solar:danger-triangle-bold-duotone"
                        width="22"
                        class="text-[var(--warning)] mt-0.5">
                    </iconify-icon>

                    <div>

                        <h3 class="font-semibold text-[var(--warning)]">
                            Perhatian
                        </h3>

                        <p class="mt-1 text-sm text-[var(--text-main)]">
                            Lakukan kenaikan dari kelas tertinggi terlebih dahulu!
                        </p>

                    </div>

                </div>

            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                @foreach($classrooms as $classroom)
                <a href="{{ route('promotions.show', $classroom->id) }}"
                class="block transition stat-card hover:shadow-md">

                    <div class="flex items-start justify-between mb-3">

                        <span class="text-caption">Kelas</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center 
                            bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon icon="solar:book-bold-duotone" width="18"></iconify-icon>
                        </div>

                    </div>

                    <div class="text-data">
                        {{ $classroom->name }}
                    </div>

                    <div class="text-xs text-[var(--text-tertiary)] mt-1">
                        {{ $classroom->students_count }} siswa
                    </div>

                </a>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout> 