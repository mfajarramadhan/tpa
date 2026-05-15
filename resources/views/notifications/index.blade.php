<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[var(--text-main)]">
            Notifikasi
        </h2>
    </x-slot>

    <div class="py-6 md:py-0">

        <div class="mx-auto max-w-7xl">

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
                                action="{{ route('notifications.destroy', $notification->id) }}">

                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Hapus notifikasi ini?')"
                                    class="badge badge-danger">

                                    <iconify-icon
                                        icon="solar:trash-bin-trash-bold-duotone">
                                    </iconify-icon>

                                    Hapus

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