<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Mapel - {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            @role('superadmin')
            <div class="mb-4">
                <a href="{{ route('learning.subject.create', $classroom->id) }}"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    + Tambah Mapel
                </a>
            </div>
            @endrole

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                @foreach($classroom->subjects as $subject)

                <div class="relative">

                    {{-- CARD CLICKABLE --}}
                    <a href="{{ route('learning.subject', $subject->id) }}"
                    class="block p-5 transition-all duration-200 bg-white border-l-4 shadow-sm rounded-2xl border-[var(--primary)] hover:shadow-md hover:-translate-y-0.5">

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
                                <h3 class="text-lg font-bold break-words text-slate-800">
                                    {{ $subject->name }}
                                </h3>

                                {{-- STATS --}}
                                <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">

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
                                class="flex items-center justify-center transition w-9 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500">

                            <iconify-icon
                                icon="solar:menu-dots-bold"
                                width="18">
                            </iconify-icon>

                        </button>

                        {{-- MENU --}}
                        <div x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute right-0 z-50 w-40 mt-2 overflow-hidden bg-white border shadow-lg rounded-xl border-slate-100">

                            {{-- EDIT --}}
                            <a href="{{ route('learning.subject.edit', $subject->id) }}"
                            @click.stop
                            class="flex items-center gap-2 px-4 py-3 text-sm transition hover:bg-slate-50">

                                <iconify-icon
                                    icon="solar:pen-bold-duotone"
                                    width="18">
                                </iconify-icon>

                                Edit

                            </a>

                            {{-- DELETE --}}
                            <form method="POST"
                                action="{{ route('learning.subject.destroy', $subject->id) }}"
                                @click.stop>

                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Yakin hapus mapel ini?')"
                                        class="flex items-center w-full gap-2 px-4 py-3 text-sm text-left text-red-600 transition hover:bg-red-50">

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