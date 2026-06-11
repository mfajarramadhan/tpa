<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Anggota - {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

            {{-- BACK --}}
            <div class="mb-6">
                <a href="{{ route('learning.classroom', $classroom->id) }}"
                    class="flex items-center gap-2 shadow-sm btn-primary w-fit">

                    <iconify-icon
                        icon="heroicons:arrow-left-20-solid"
                        width="20">
                    </iconify-icon>

                    Kembali
                </a>
            </div>

            {{-- GURU --}}
            <section class="mb-9">

                <div class="flex items-end justify-between pb-4 border-b-2 border-[var(--primary)]">
                    <h3 class="text-3xl font-semibold text-[var(--text-main)]">
                        Guru
                    </h3>

                    <span class="text-sm font-medium text-[var(--text-tertiary)]">
                        {{ $teachers->count() }} guru
                    </span>
                </div>

                <div>
                    @forelse($teachers as $teacher)
                        @php
                            $initial = strtoupper(substr($teacher->name, 0, 1));
                        @endphp

                        <div class="flex items-center gap-6 px-4 py-4 border-b-2 border-[var(--primary-light)]">

                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-[var(--primary)] text-white flex items-center justify-center font-bold text-base shrink-0">
                                {{ $initial }}
                            </div>

                            {{-- Nama --}}
                            <div class="text-base font-semibold text-[var(--text-main)]">
                                {{ $teacher->name }}
                            </div>

                        </div>
                    @empty
                        <div class="py-5 text-sm text-[var(--text-tertiary)]">
                            Belum ada data guru.
                        </div>
                    @endforelse
                </div>

            </section>

            {{-- SISWA --}}
            <section>

                <div class="flex items-end justify-between pb-4 border-b-2 border-[var(--primary)]">
                    <h3 class="text-3xl font-semibold text-[var(--text-main)]">
                        Siswa
                    </h3>

                    <span class="text-sm font-medium text-[var(--text-tertiary)]">
                        {{ $students->count() }} siswa
                    </span>
                </div>

                <div>
                    @forelse($students as $student)
                        @php
                            $initial = strtoupper(substr($student->name, 0, 1));
                        @endphp

                        <div class="flex items-center gap-6 px-4 py-4 border-b border-[var(--border)]">

                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-[var(--primary)] text-white flex items-center justify-center font-bold text-base shrink-0">
                                {{ $initial }}
                            </div>

                            {{-- Nama --}}
                            <div class="text-base font-semibold text-[var(--text-main)]">
                                {{ $student->name }}
                            </div>

                        </div>
                    @empty
                        <div class="py-5 text-sm text-[var(--text-tertiary)]">
                            Belum ada data siswa di kelas ini.
                        </div>
                    @endforelse
                </div>

            </section>

        </div>
    </div>
</x-app-layout>