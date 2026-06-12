<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kelola User</h2>
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

        </div>

            <div class="flex justify-end mb-6">
                <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 shadow-md btn-primary">
                    <iconify-icon icon="solar:user-plus-bold-duotone" width="20"></iconify-icon>
                    Tambah User
                </a>
            </div>

            {{-- FILTER --}}
            <form method="GET" class="mb-4">

                <div class="flex flex-col gap-3 md:flex-row md:flex-wrap md:items-center">

                    {{-- ROW 1 MOBILE --}}
                    <div class="flex gap-2 md:contents">

                        {{-- SEARCH --}}
                        <input type="text"
                            name="name"
                            value="{{ request('name') }}"
                            placeholder="Cari user..."
                            class="input-solid flex-1 md:flex-none md:w-fit md:max-w-40
                            bg-[var(--surface)]
                            rounded-xl
                            py-2.5 px-3
                            border-2 border-[var(--border)]
                            shadow-md
                            focus:border-[var(--primary)]">

                        {{-- ROLE --}}
                        <select name="role"
                            class="input-solid flex-1 md:flex-none md:max-w-40 md:min-w-[150px]
                            bg-[var(--surface)]
                            rounded-xl
                            py-2.5
                            border-2 border-[var(--border)]
                            shadow-md
                            focus:border-[var(--primary)]">

                            <option value="">Semua Role</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="guru">Guru</option>
                            <option value="orang_tua">Orang Tua</option>
                            <option value="siswa">Siswa</option>

                        </select>

                    </div>

                    {{-- ROW 2 MOBILE --}}
                    <div class="flex gap-2 md:contents">

                        {{-- STATUS --}}
                        <select name="status"
                            class="input-solid flex-[2]
                            md:flex-none md:max-w-40 md:min-w-[150px]
                            bg-[var(--surface)]
                            rounded-xl
                            py-2.5
                            border-2 border-[var(--border)]
                            shadow-md
                            focus:border-[var(--primary)]">

                            <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>
                                Nonaktif
                            </option>

                            <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>
                                Dihapus
                            </option>

                        </select>

                        {{-- FILTER BUTTON --}}
                        <button
                            class="flex-1 md:flex-none
                            flex items-center justify-center gap-2
                            rounded-xl
                            py-2.5 px-4
                            border-2 border-[var(--primary)]
                            bg-[var(--primary)]
                            text-white
                            shadow-md
                            hover:opacity-90
                            transition-all duration-200">

                            <iconify-icon
                                icon="solar:filter-bold-duotone"
                                width="20">
                            </iconify-icon>

                            <span class="hidden sm:inline">
                                Filter
                            </span>

                        </button>

                        {{-- CLEAR --}}
                        <a href="{{ route('users.index') }}"
                            class="flex-1 md:flex-none
                            flex items-center justify-center
                            rounded-xl
                            py-2.5 px-3
                            border-2 border-gray-500
                            bg-gray-500
                            text-white
                            shadow-md
                            hover:bg-gray-600
                            hover:border-gray-600
                            transition-all duration-200">

                            <iconify-icon
                                icon="solar:close-circle-bold-duotone"
                                width="22">
                            </iconify-icon>

                        </a>

                    </div>

                </div>

            </form>

            {{-- TABLE --}}
            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th class="w-[28%]">Nama</th>
                            <th class="w-[28%]">Email</th>
                            <th class="w-[16%]">Role</th>
                            <th class="w-[14%]">Status</th>
                            <th class="w-[14%] !text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $user->trashed() ? 'opacity-75' : '' }}">

                                @php
                                    $initial = strtoupper(substr($user->name, 0, 2));
                                @endphp
                                
                                {{-- NAMA --}}
                                <td class="font-semibold text-[var(--text-main)]">
                                    <div class="flex items-center gap-4">

                                        {{-- Avatar Inisial --}}
                                        <div class="w-10 h-10 rounded-full bg-[var(--primary-light)] text-[var(--primary)] flex items-center justify-center font-bold text-sm border border-[var(--primary-light)]">
                                            {{ $initial }}
                                        </div>

                                        {{-- Nama + Info kecil --}}
                                        <div>
                                            <div class="font-semibold text-[var(--text-main)]">
                                                {{ $user->name }}
                                            </div>
                                            @if($user->hasRole('siswa') && $user->student)
                                            <div class="text-xs text-[var(--text-tertiary)] mt-0.5">
                                                {{ $user->student->classroom->name ?? '-' }}
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="text-small">
                                    {{ $user->email }}
                                </td>

                                {{-- ROLE --}}
                                <td>
                                    @php
                                        $role = $user->roles->first()->name ?? '';

                                        $roleConfig = [
                                            'superadmin' => [
                                                'label' => 'Superadmin',
                                                'class' => 'badge-danger',
                                                'icon' => 'solar:shield-user-bold-duotone',
                                            ],
                                            'guru' => [
                                                'label' => 'Guru',
                                                'class' => 'badge-warning',
                                                'icon' => 'solar:user-check-bold-duotone',
                                            ],
                                            'orang_tua' => [
                                                'label' => 'Orang Tua',
                                                'class' => 'badge-success',
                                                'icon' => 'solar:user-bold-duotone',
                                            ],
                                            'siswa' => [
                                                'label' => 'Siswa',
                                                'class' => 'badge-info',
                                                'icon' => 'solar:people-nearby-bold-duotone',
                                            ],
                                        ];

                                        $config = $roleConfig[$role] ?? [
                                            'label' => '-',
                                            'class' => 'badge-secondary',
                                            'icon' => 'solar:user-id-bold-duotone',
                                        ];
                                    @endphp

                                    <span class="badge {{ $config['class'] }}">
                                        <iconify-icon icon="{{ $config['icon'] }}"></iconify-icon>
                                        {{ $config['label'] }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($user->trashed())
                                        <span class="badge badge-danger">
                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                            Deleted
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('users.toggleStatus', $user->id) }}">
                                            @csrf
                                            <button class="badge {{ $user->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                                <iconify-icon icon="{{ $user->status == 'aktif' ? 'solar:check-circle-bold-duotone' : 'solar:close-circle-bold-duotone' }}"></iconify-icon>
                                                {{ $user->status }}
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td>
                                    <div class="flex justify-center gap-2">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('users.show', $user->id) . '?' . request()->getQueryString() }}"
                                           class="btn-icon border border-[var(--primary)]" title="Detail">
                                            <iconify-icon icon="solar:eye-bold-duotone"
                                                          class="text-[var(--primary)]"></iconify-icon>
                                        </a>

                                        @if(!$user->trashed())

                                            {{-- EDIT --}}
                                            <a href="{{ route('users.edit', $user->id) . '?' . request()->getQueryString() }}"
                                               title="Edit" class="btn-icon group bg-[var(--warning-light)] border border-[var(--warning-dark)] hover:bg-[var(--warning-dark)]">
                                                <iconify-icon icon="heroicons:pencil-square"
                                                              class="text-[var(--warning-dark)] group-hover:text-white"></iconify-icon>
                                            </a>

                                            {{-- DELETE --}}
                                            <form method="POST" 
                                                action="{{ route('users.destroy', $user->id) }}" 
                                                onsubmit="confirmAction(
                                                    event,
                                                    'Hapus User?',
                                                    'Data user akan dihapus!',
                                                    'Ya, Hapus',
                                                    'warning'
                                                )">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        title="Hapus" class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]">
                                                    <iconify-icon icon="heroicons:trash"
                                                                  class="text-[var(--danger)] group-hover:text-white"></iconify-icon>
                                                </button>
                                            </form>

                                        @else

                                            <form method="POST"
                                                action="{{ route('users.restore', $user->id) }}"
                                                onsubmit="confirmAction(
                                                    event,
                                                    'Pulihkan User?',
                                                    'User akan diaktifkan kembali',
                                                    'Ya, Pulihkan',
                                                    'question'
                                                )">

                                                @csrf

                                                <button type="submit"
                                                    class="group btn-icon bg-[var(--info-light)] border border-[var(--info)] hover:bg-[var(--info)]">
                                                    <iconify-icon icon="solar:restart-bold"
                                                                  class="text-[var(--info)] group-hover:text-white"></iconify-icon>
                                                </button>
                                            </form>

                                            {{-- FORCE DELETE --}}
                                            <form method="POST"
                                                    action="{{ route('users.forceDelete', $user->id) }}"
                                                    onsubmit="confirmAction(
                                                        event,
                                                        'Hapus Permanen?',
                                                        'Data tidak dapat dikembalikan setelah dihapus',
                                                        'Ya, Hapus',
                                                        'error'
                                                    )">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]">
                                                    <iconify-icon icon="heroicons:trash"
                                                                  class="text-[var(--danger)] group-hover:text-white"></iconify-icon>
                                                </button>
                                            </form>

                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-small">
                                    Tidak ada data user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
                {{-- Pagination --}}
                @if($users->hasPages())

                    <div class="flex items-center justify-between p-5 border-t border-[var(--border-light)] bg-[var(--surface)]">

                        {{-- Info --}}
                        <div class="text-sm font-medium text-[var(--text-tertiary)]">

                            Menampilkan
                            {{ $users->firstItem() ?? 0 }}
                            -
                            {{ $users->lastItem() ?? 0 }}

                            dari

                            {{ $users->total() }}

                            data

                        </div>

                        {{-- Button --}}
                        <div class="flex gap-2">

                            {{-- Prev --}}
                            @if($users->onFirstPage())

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    &laquo; Prev
                                </span>

                            @else

                                <a href="{{ $users->previousPageUrl() }}"
                                class="px-3 py-1.5 text-sm border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    &laquo; Prev

                                </a>

                            @endif

                            {{-- Number --}}
                            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)

                                @if($page == $users->currentPage())

                                    <span class="px-3.5 py-1.5 text-sm shadow-md btn-primary">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $url }}"
                                    class="px-3.5 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                        {{ $page }}

                                    </a>

                                @endif

                            @endforeach

                            {{-- Next --}}
                            @if($users->hasMorePages())

                                <a href="{{ $users->nextPageUrl() }}"
                                class="px-3 py-1.5 text-sm font-medium border-transparent btn-outline hover:bg-[var(--border-light)]">

                                    Next &raquo;

                                </a>

                            @else

                                <span class="px-3 py-1.5 text-sm opacity-50 cursor-not-allowed btn-outline">
                                    Next &raquo;
                                </span>

                            @endif

                        </div>

                    </div>

                @endif
            </div>

        </div>
    </div>
</x-app-layout>