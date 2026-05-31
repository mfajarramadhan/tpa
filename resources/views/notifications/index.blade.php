<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[var(--text-main)]">
            Notifikasi
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

            {{-- Previous --}}
            <div class="mb-6">
                <a href="{{ url()->previous() }}"
                class="flex items-center gap-2 shadow-sm btn-primary">

                    <iconify-icon
                        icon="heroicons:arrow-left-20-solid"
                        width="20">
                    </iconify-icon>

                    Kembali

                </a>
            </div>
            
            {{-- LIST --}}
            <div class="space-y-4">

                @forelse($notifications as $notification)

                    <div class="p-5 border shadow-sm rounded-2xl {{ is_null($notification->read_at) ? 'bg-[var(--primary-light)] border-[var(--primary)]/30' : 'bg-surface border-custom'}}">

                        <div class="flex items-center justify-between gap-4">

                            {{-- CONTENT --}}
                            <div class="flex items-center gap-4">

                                {{-- ICON --}}
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-xl bg-[var(--primary-light)] text-[var(--primary)]">

                                    <iconify-icon
                                        icon="solar:bell-bing-bold-duotone"
                                        width="22">
                                    </iconify-icon>

                                </div>

                                {{-- TEXT --}}
                                <div>

                                    {{-- TITLE --}}
                                    <h3 class="text-[var(--text-main)] {{ is_null($notification->read_at) ? 'font-bold' : 'font-semibold'}}">
                                        {{ $notification->data['title'] }}
                                    </h3>

                                    {{-- MESSAGE --}}
                                    <p class="mt-1 text-sm text-[var(--text-secondary)]">
                                        {{ $notification->data['message'] }}
                                    </p>

                                    {{-- TIME --}}
                                    <div class="mt-2 text-xs text-[var(--text-tertiary)]">

                                        {{ $notification->created_at->diffForHumans() }}

                                    </div>

                                </div>

                            </div>

                            {{-- DELETE --}}
                            <form method="POST"
                                action="{{ route('notifications.destroy', $notification->id) }}"
                                onsubmit="confirmAction(
                                    event,
                                    'Hapus Notifikasi?',
                                    'Notifikasi akan dihapus!',
                                    'Ya, Hapus',
                                    'warning'
                                )">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    title="Hapus"
                                    class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]">

                                    <iconify-icon
                                        icon="solar:trash-bin-trash-bold-duotone"
                                        width="16"
                                        class="text-[var(--danger)] group-hover:text-white">
                                    </iconify-icon>

                                </button>

                            </form>

                        </div>

                    </div>

                @empty

                    {{-- EMPTY --}}
                    <div class="p-10 text-center border shadow-sm bg-surface border-custom rounded-2xl">

                        <div class="flex justify-center mb-4 text-[var(--text-tertiary)]">

                            <iconify-icon
                                icon="solar:bell-off-bold-duotone"
                                width="60">
                            </iconify-icon>

                        </div>

                        <h3 class="text-lg font-semibold text-[var(--text-main)]">
                            Belum Ada Notifikasi
                        </h3>

                        <p class="mt-1 text-sm text-[var(--text-secondary)]">
                            Semua aktivitas terbaru akan muncul di sini.
                        </p>

                    </div>

                @endforelse

            </div>

            {{-- PAGINATION --}}
            @if($notifications->hasPages())

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>