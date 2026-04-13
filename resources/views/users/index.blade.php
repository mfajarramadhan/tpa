<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kelola User</h2>
    </x-slot>

    <div class="py-6 mx-auto max-w-7xl">

        {{-- FILTER --}}
        <form method="GET" class="flex gap-2 mb-4">
            <input type="text" name="name" placeholder="Cari nama"
                   class="px-3 py-2 border rounded">

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Cari
            </button>
        </form>

        <div class="overflow-hidden bg-white rounded shadow">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3">{{ $user->name }}</td>
                            <td class="p-3">{{ $user->email }}</td>

                            {{-- ROLE --}}
                            <td class="p-3">
                                <form method="POST"
                                      action="{{ route('users.updateRole', $user->id) }}">
                                    @csrf

                                    <select name="role"
                                            onchange="this.form.submit()"
                                            class="px-2 py-1 border rounded">

                                        <option value="orang_tua"
                                            {{ $user->hasRole('orang_tua') ? 'selected' : '' }}>
                                            Orang Tua
                                        </option>

                                        <option value="guru"
                                            {{ $user->hasRole('guru') ? 'selected' : '' }}>
                                            Guru
                                        </option>

                                        <option value="siswa"
                                            {{ $user->hasRole('siswa') ? 'selected' : '' }}>
                                            Siswa
                                        </option>

                                    </select>
                                </form>
                            </td>

                            {{-- STATUS --}}
                            <td class="p-3">
                                <span class="px-2 py-1 text-white rounded
                                    {{ $user->status == 'aktif' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $user->status }}
                                </span>
                            </td>

                            {{-- TOGGLE --}}
                            <td class="p-3">
                                @if($user->hasRole('guru'))
                                    <form method="POST"
                                          action="{{ route('users.toggleStatus', $user->id) }}">
                                        @csrf
                                        <button class="px-2 py-1 text-white bg-gray-600 rounded">
                                            Toggle Status
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>