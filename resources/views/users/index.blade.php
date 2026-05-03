<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kelola User</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

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

            <div class="mb-4">
                <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 shadow-sm btn-primary">
                    <iconify-icon icon="solar:user-plus-bold-duotone" width="20"></iconify-icon>
                    Tambah User
                </a>
            </div>

            {{-- 🔍 FILTER --}}
            <form method="GET" class="flex flex-wrap items-center gap-2 mb-4">

                <input type="text"
                    name="name"
                    value="{{ request('name') }}"
                    placeholder="Cari nama..."
                    class="input-solid inline-block flex-none w-fit max-w-40 bg-[var(--surface)] rounded-xl py-2.5 px-3">

                <select name="role" class="input-solid max-w-40 w-fit min-w-[150px] bg-[var(--surface)] rounded-xl py-2.5">
                    <option value="">Semua Role</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="guru">Guru</option>
                    <option value="orang_tua">Orang Tua</option>
                    <option value="siswa">Siswa</option>
                </select>

                <select name="status" class="input-solid max-w-40 w-fit min-w-[150px] bg-[var(--surface)] rounded-xl py-2.5">
                    {{-- <option value="" {{ $status === '' ? 'selected' : '' }}>Semua Status</option> --}}
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>Dihapus</option>
                </select>

                <button class="btn-outline flex items-center gap-2 rounded-xl py-2.5 border-[var(--border)] hover:bg-[var(--primary-light)] hover:border-[var(--primary)] hover:text-[var(--primary)]">
                    <iconify-icon icon="solar:filter-bold-duotone" width="20"></iconify-icon>
                    Filter
                </button>

                <!-- Button Clear -->
                <a href="{{ route('users.index') }}"
                    class="btn-outline flex items-center justify-center rounded-xl py-2.5 border-[var(--border)] hover:bg-[var(--primary-light)] hover:border-[var(--primary)] hover:text-[var(--primary)]">
                    <iconify-icon icon="solar:close-circle-bold-duotone" width="22"></iconify-icon>
                </a>

            </form>

            {{-- TABLE --}}
            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">

                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $user->trashed() ? 'opacity-50' : '' }}">
                                {{-- NAMA --}}
                                <td class="font-semibold text-[var(--text-main)]">
                                    {{ $user->name }}
                                </td>

                                {{-- EMAIL --}}
                                <td class="text-small">
                                    {{ $user->email }}
                                </td>

                                {{-- ROLE --}}
                                <td>
                                    <span class="badge badge-info">
                                        <iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>
                                        {{ $user->roles->first()->name ?? '-' }}
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
                                        <a href="{{ route('users.show', $user->id) }}"
                                           class="btn-icon border border-[var(--primary)]" title="Detail">
                                            <iconify-icon icon="solar:eye-bold-duotone"
                                                          class="text-[var(--primary)]"></iconify-icon>
                                        </a>

                                        @if(!$user->trashed())

                                            {{-- EDIT --}}
                                            <a href="{{ route('users.edit', $user->id) }}"
                                               title="Edit" class="btn-icon group bg-[var(--warning-light)] border border-[var(--warning-dark)] hover:bg-[var(--warning-dark)]">
                                                <iconify-icon icon="heroicons:pencil-square"
                                                              class="text-[var(--warning-dark)] group-hover:text-white"></iconify-icon>
                                            </a>

                                            {{-- DELETE --}}
                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button onclick="return confirm('Yakin hapus user?')"
                                                        title="Hapus" class="btn-icon group bg-[var(--danger-light)] border border-[var(--danger)] hover:bg-[var(--danger)]">
                                                    <iconify-icon icon="heroicons:trash"
                                                                  class="text-[var(--danger)] group-hover:text-white"></iconify-icon>
                                                </button>
                                            </form>

                                        @else

                                            {{-- RESTORE --}}
                                            <form method="POST" action="{{ route('users.restore', $user->id) }}">
                                                @csrf

                                                <button class="btn-icon bg-[var(--info-light)] border border-[var(--info)] hover:bg-[var(--info)]">
                                                    <iconify-icon icon="solar:restart-bold-duotone"
                                                                  class="text-[var(--info)] group-hover:text-white"></iconify-icon>
                                                </button>
                                            </form>

                                            {{-- FORCE DELETE --}}
                                            <form method="POST" action="{{ route('users.forceDelete', $user->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button onclick="return confirm('Hapus permanen?')"
                                                        class="btn-icon bg-[var(--danger)] text-white">
                                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
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
            </div>

        </div>
    </div>
</x-app-layout>