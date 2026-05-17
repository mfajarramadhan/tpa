<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Tahun Akademik
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

            
            {{-- FORM --}}
            <div class="p-5 mb-6 border shadow-sm bg-surface border-custom rounded-2xl">

                <form method="POST"
                    action="{{ route('academic-years.store') }}">

                    @csrf

                    {{-- HEADER --}}
                    <div class="flex items-center gap-3 px-0 pb-5 mb-6 border-b border-custom">

                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[var(--primary-light)]">

                            <iconify-icon
                                icon="solar:calendar-bold-duotone"
                                class="text-xl text-[var(--primary)]">
                            </iconify-icon>

                        </div>

                        <div>

                            <h2 class="text-xl font-bold text-[var(--text-main)]">
                                Tambah Tahun Akademik
                            </h2>

                            <p class="text-sm text-[var(--text-tertiary)]">
                                Tambahkan tahun akademik baru
                            </p>

                        </div>

                    </div>

                    {{-- FORM --}}
                    <div>

                        <div class="flex gap-3">

                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                maxlength="9"
                                inputmode="numeric"
                                pattern="[0-9/]*"
                                oninput="this.value = this.value.replace(/[^0-9/]/g, '')"
                                placeholder="Contoh: 2025/2026"
                                class="input-solid w-full bg-[var(--surface)] border-2 shadow-sm rounded-xl
                                {{ $errors->has('name')
                                    ? 'border-red-500'
                                    : 'border-[var(--border)] focus:border-[var(--primary)]' }}">

                            <button class="shadow-sm btn-primary">

                                <iconify-icon
                                    icon="solar:add-circle-bold-duotone"
                                    width="20">
                                </iconify-icon>

                                Tambah

                            </button>

                        </div>

                        @error('name')

                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </form>

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto card-panel">

                <table class="w-full text-sm table-custom">

                    <thead>

                        <tr>

                            <th class="w-[40%]">
                                Tahun
                            </th>

                            <th class="w-[30%]">
                                Status
                            </th>

                            <th class="w-[30%] !text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($years as $year)

                            <tr>

                                {{-- TAHUN --}}
                                <td class="font-semibold text-[var(--text-main)]">

                                    {{ $year->name }}

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    @if($year->is_active)

                                        <span class="badge badge-success">

                                            <iconify-icon
                                                icon="solar:check-circle-bold-duotone">
                                            </iconify-icon>

                                            Aktif

                                        </span>

                                    @else

                                        <span class="badge badge-danger">

                                            <iconify-icon
                                                icon="solar:close-circle-bold-duotone">
                                            </iconify-icon>

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td>

                                    <div class="flex justify-center gap-2">

                                        {{-- SET ACTIVE --}}
                                        @if(!$year->is_active)

                                            <form method="POST"
                                                action="{{ route('academic-years.setActive', $year->id) }}">

                                                @csrf

                                                <button class="flex items-center gap-1 px-3 py-2 text-xs shadow-sm rounded-lg transition bg-[var(--primary-light)] border border-[var(--primary)] text-[var(--primary)] hover:bg-[var(--primary)] hover:text-white">

                                                    <iconify-icon
                                                        icon="solar:check-circle-bold-duotone"
                                                        width="16">
                                                    </iconify-icon>

                                                    Set Aktif

                                                </button>

                                            </form>

                                        @endif

                                        {{-- EDIT --}}
                                        <a href="{{ route('academic-years.edit', $year->id) }}"
                                            title="Edit"
                                            class="btn-icon group bg-[var(--warning-light)] border border-[var(--warning-dark)] hover:bg-[var(--warning-dark)]">

                                            <iconify-icon
                                                icon="heroicons:pencil-square"
                                                class="text-[var(--warning-dark)] group-hover:text-white">
                                            </iconify-icon>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3"
                                    class="py-6 text-center text-small">

                                    Tidak ada data tahun akademik

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-app-layout>