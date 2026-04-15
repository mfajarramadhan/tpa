<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kelola User</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('users.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    + Tambah User
                </a>
            </div>

            {{-- 🔍 FILTER --}}
            <form method="GET" class="flex gap-2 mb-4">

                <input type="text"
                    name="name"
                    value="{{ request('name') }}"
                    placeholder="Cari nama..."
                    class="p-2 border rounded">

                <select name="role" class="p-2 border rounded">
                    <option value="">Semua Role</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="guru">Guru</option>
                    <option value="orang_tua">Orang Tua</option>
                    <option value="siswa">Siswa</option>
                </select>

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Filter
                </button>

            </form>

            {{-- TABLE --}}
            <div class="overflow-hidden bg-white rounded shadow">
                <table class="w-full text-sm">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Role</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-t hover:bg-gray-50">

                                {{-- NAMA --}}
                                <td class="p-3">{{ $user->name }}</td>

                                {{-- EMAIL --}}
                                <td class="p-3">{{ $user->email }}</td>

                                {{-- ROLE DROPDOWN --}}
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs text-white bg-blue-500 rounded">
                                        {{ $user->roles->first()->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td class="p-3">
                                    <form method="POST" action="{{ route('users.toggleStatus', $user->id) }}">
                                        @csrf

                                        <button class="px-2 py-1 text-xs text-white rounded
                                            {{ $user->status == 'aktif' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $user->status }}
                                        </button>
                                    </form>
                                </td>

                                {{-- AKSI --}}
                                <td class="flex gap-2 p-3">

                                    <a href="{{ route('users.show', $user->id) }}"
                                       class="px-2 py-1 text-xs text-white bg-blue-500 rounded">
                                        Detail
                                    </a>

                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button onclick="return confirm('Yakin hapus user ini?')"
                                                class="px-2 py-1 text-xs text-white bg-red-600 rounded">
                                            Hapus
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">
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