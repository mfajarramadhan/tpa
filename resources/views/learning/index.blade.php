<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Pilih Kelas</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                @foreach($classrooms as $classroom)
                <a href="{{ route('learning.classroom', $classroom->id) }}"
                   class="block transition stat-card hover:shadow-md">

                    <div class="flex items-start justify-between mb-3">
                        <span class="text-caption">Kelas</span>

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center 
                            bg-[var(--primary-light)] text-[var(--primary)]">
                            <iconify-icon icon="solar:notebook-bold-duotone" width="18"></iconify-icon>
                        </div>
                    </div>

                    <div class="text-data">
                        {{ $classroom->name }}
                    </div>

                    <div class="text-xs text-[var(--text-tertiary)] mt-1">
                        {{ $classroom->subjects_count ?? 0 }} mata pelajaran
                    </div>

                </a>
                @endforeach

            </div>

        </div>
    </div>
</x-app-layout>