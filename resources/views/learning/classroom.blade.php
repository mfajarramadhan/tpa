<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Mapel - {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

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
            
            <div class="flex items-center justify-between mb-6">

            {{-- BACK BUTTON --}}
            <a href="{{ route('learning.index') }}"
            class="flex items-center gap-2 shadow-sm btn-primary">

                <iconify-icon
                    icon="heroicons:arrow-left-20-solid"
                    width="20">
                </iconify-icon>

                Kembali

            </a>

            {{-- TAMBAH MAPEL --}}
            @role('superadmin')

                <a href="{{ route('learning.subject.create', $classroom->id) }}"
                class="flex items-center gap-2 shadow-md btn-primary">

                    <iconify-icon
                        icon="solar:add-circle-bold-duotone"
                        width="20">
                    </iconify-icon>

                    Tambah Mapel

                </a>

            @endrole

        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">

        @foreach($classroom->subjects as $subject)

            <div class="relative">

                {{-- CARD --}}
                <a
                    @unlessrole('superadmin')
                        href="{{ route('learning.subject', $subject->id) }}"
                    @endunlessrole

                    class="block p-5 transition-all duration-200 bg-surface border border-custom border-l-4 !border-l-[var(--primary)] shadow-sm rounded-2xl hover:shadow-md hover:-translate-y-0.5">

                    {{-- HEADER --}}
                    <div class="flex items-start justify-between gap-3 mb-4">

                        <div>

                            {{-- BADGE HARI --}}
                            <div class="inline-flex items-center gap-1 px-3 py-1 mb-3 text-xs font-semibold rounded-full bg-[var(--primary-light)] text-[var(--primary)]">

                                <iconify-icon
                                    icon="solar:calendar-bold-duotone"
                                    width="14">
                                </iconify-icon>

                                {{ $subject->day_name }}

                            </div>

                            {{-- TITLE --}}
                            <h3 class="text-lg font-bold break-words text-[var(--text-main)]">
                                {{ $subject->name }}
                            </h3>

                            {{-- STATS --}}
                            <div class="flex items-center gap-2 mt-2 text-xs text-[var(--text-tertiary)]">

                                <div class="flex items-center gap-1">

                                    <iconify-icon
                                        icon="solar:document-text-bold-duotone"
                                        width="14">
                                    </iconify-icon>

                                    {{ $subject->materials_count ?? 0 }} materi

                                </div>

                                <span>|</span>

                                <div class="flex items-center gap-1">

                                    <iconify-icon
                                        icon="solar:clipboard-check-bold-duotone"
                                        width="14">
                                    </iconify-icon>

                                    {{ $subject->tasks_count ?? 0 }} tugas

                                </div>

                            </div>

                        </div>

                    </div>

                </a>

                {{-- 🔥 DROPDOWN (SUPERADMIN ONLY) --}}
                @role('superadmin')

                <div x-data="{ open: false }"
                    class="absolute top-4 right-4">

                    {{-- BUTTON --}}
                    <button @click.stop="open = !open"
                            class="flex items-center justify-center w-9 h-9 transition rounded-xl bg-[var(--bg)] hover:bg-[var(--primary-light)] text-[var(--text-secondary)] border border-custom shadow-sm">

                        <iconify-icon
                            icon="solar:menu-dots-bold"
                            width="18">
                        </iconify-icon>

                    </button>

                    {{-- MENU --}}
                    <div x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="absolute right-0 z-50 w-40 mt-2 overflow-hidden border shadow-lg bg-surface border-custom rounded-xl">

                        {{-- EDIT --}}
                        <a href="{{ route('learning.subject.edit', $subject->id) }}"
                        @click.stop
                        class="flex items-center gap-2 px-4 py-3 text-sm transition text-[var(--text-main)] hover:bg-[var(--primary-light)] hover:text-[var(--primary)]">

                            <iconify-icon
                                icon="solar:pen-bold-duotone"
                                width="18">
                            </iconify-icon>

                            Edit

                        </a>

                        {{-- DELETE --}}
                        <form method="POST"
                            action="{{ route('learning.subject.destroy', $subject->id) }}"
                            @click.stop
                            onsubmit="confirmAction(
                                event,
                                'Hapus Mata Pelajaran?',
                                'Data mata pelajaran akan dihapus permanen',
                                'Ya, Hapus',
                                'error'
                            )">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="flex items-center w-full gap-2 px-4 py-3 text-sm text-left text-red-500 transition hover:bg-red-500/10">

                                <iconify-icon
                                    icon="solar:trash-bin-trash-bold-duotone"
                                    width="18">
                                </iconify-icon>

                                Hapus

                            </button>

                        </form>

                    </div>

                </div>

                @endrole

            </div>

        @endforeach

        </div>
    </div>
</x-app-layout>