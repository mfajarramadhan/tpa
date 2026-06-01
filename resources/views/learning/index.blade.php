<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Daftar Kelas
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

            {{-- TAMBAH KELAS --}}
            @role('superadmin')
            <div class="flex justify-end mb-6">

                <a href="{{ route('learning.classroom.create') }}"
                class="flex items-center gap-2 shadow-md btn-primary">

                    <iconify-icon
                        icon="solar:add-circle-bold-duotone"
                        width="20">
                    </iconify-icon>

                    Tambah Kelas

                </a>

            </div>
            @endrole

            @role('orang_tua')
                @if(!Auth::user()->students()->exists())
                    <div class="m-auto text-center">
                        <p class="text-gray-500">
                            Belum ada anak yang terdaftar!
                        </p>
                    </div>
                @endif
            @endrole

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            @foreach($classrooms as $classroom)

                <div class="relative">

                    {{-- CARD --}}
                    <a href="{{ route('learning.classroom', $classroom->id) }}"
                    class="block p-5 transition-all duration-200 bg-surface border border-custom border-l-4 !border-l-[var(--primary)] shadow-sm rounded-2xl hover:shadow-md hover:-translate-y-0.5">

                        {{-- HEADER --}}
                        <div class="flex items-start justify-between gap-3 mb-4">

                            <div>

                                {{-- BADGE --}}
                                <div class="inline-flex items-center gap-1 px-3 py-1 mb-3 text-xs font-semibold rounded-full bg-[var(--primary-light)] text-[var(--primary)]">

                                    <iconify-icon
                                        icon="solar:notebook-bold-duotone"
                                        width="14">
                                    </iconify-icon>

                                    Kelas

                                </div>

                                {{-- TITLE --}}
                                <h3 class="text-lg font-bold break-words text-[var(--text-main)]">
                                    {{ $classroom->name }}
                                </h3>

                                {{-- STATS --}}
                                <div class="flex items-center gap-2 mt-2 text-xs text-[var(--text-tertiary)]">

                                    <div class="flex items-center gap-1">

                                        <iconify-icon
                                            icon="solar:book-bookmark-bold-duotone"
                                            width="14">
                                        </iconify-icon>

                                        {{ $classroom->subjects_count ?? 0 }} mata pelajaran

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                    {{-- DROPDOWN --}}
                    @role('superadmin')

                    <div x-data="{ open: false }" class="absolute top-4 right-4">

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
                            <a href="{{ route('learning.classroom.edit', $classroom->id) }}"
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
                                action="{{ route('learning.classroom.destroy', $classroom->id) }}"
                                @click.stop
                                onsubmit="confirmAction(
                                    event,
                                    'Hapus Kelas?',
                                    'Data kelas akan dihapus permanen',
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
    </div>
</x-app-layout>