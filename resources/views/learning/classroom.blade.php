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
                    class="block p-5 bg-white rounded-lg shadow hover:shadow-md">

                        <h3 class="font-bold">
                            {{ $subject->name }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $subject->description }}
                        </p>

                        <div class="mt-1 text-xs text-gray-500">
                            {{ $subject->materials_count ?? 0 }} materi
                        </div>

                    </a>

                    {{-- 🔥 DROPDOWN (SUPERADMIN ONLY) --}}
                    @role('superadmin')
                    <div x-data="{ open: false }"
                        class="absolute top-3 right-3">

                        {{-- BUTTON --}}
                        <button @click.stop="open = !open"
                                class="px-2 py-1 text-gray-500 rounded hover:bg-gray-100">
                            ⋮
                        </button>

                        {{-- MENU --}}
                        <div x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute right-0 z-50 mt-2 bg-white border rounded-lg shadow w-36">

                            {{-- EDIT --}}
                            <a href="{{ route('learning.subject.edit', $subject->id) }}"
                            @click.stop
                            class="block px-3 py-2 text-sm hover:bg-gray-100">
                                Edit
                            </a>

                            {{-- DELETE --}}
                            <form method="POST"
                                action="{{ route('learning.subject.destroy', $subject->id) }}"
                                @click.stop>
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Yakin hapus mapel ini?')"
                                        class="w-full px-3 py-2 text-sm text-left text-red-600 hover:bg-gray-100">
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