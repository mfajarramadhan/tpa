<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kelola User</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white shadow-sm rounded-2xl">

            <h2 class="mb-4 text-lg font-semibold">Detail User</h2>

            {{-- DATA USER --}}
            <div class="mb-4">
                <p><strong>Nama:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
                <p><strong>Status:</strong>
                    <span class="px-2 py-1 text-xs text-white rounded
                        {{ $user->status == 'aktif' ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </p>

                <p><strong>Role:</strong>
                    {{ $user->getRoleNames()->implode(', ') }}
                </p>
            </div>

            {{-- 🔥 DATA SISWA (JIKA ADA) --}}
            @if($user->student)

                <hr class="my-4">

                <h3 class="mb-2 font-semibold">Data Siswa</h3>

                <div class="space-y-1 text-sm">

                    <p><strong>Kelas:</strong>
                        {{ $user->student->classroom->name ?? '-' }}
                    </p>

                    <p><strong>NISN:</strong>
                        {{ $user->student->nisn ?? '-' }}
                    </p>

                    <p><strong>Tanggal Lahir:</strong>
                        {{ $user->student->birth_date
                            ? \Carbon\Carbon::parse($user->student->birth_date)->format('d M Y')
                            : '-' }}
                    </p>

                    <p><strong>Gender:</strong>
                        @if($user->student->gender == 'L')
                            Laki-laki
                        @elseif($user->student->gender == 'P')
                            Perempuan
                        @else
                            -
                        @endif
                    </p>

                    <p><strong>Asal Sekolah:</strong>
                        {{ $user->student->school_origin ?? '-' }}
                    </p>

                </div>

            @endif

            {{-- BUTTON --}}
            <div class="mt-4">
                <a href="{{ route('users.edit', $user->id) }}"
                class="px-4 py-2 text-white bg-blue-600 rounded">
                    Edit
                </a>

                <a href="{{ route('users.index') }}"
                class="px-4 py-2 ml-2 text-gray-700 bg-gray-200 rounded">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</x-app-layout>