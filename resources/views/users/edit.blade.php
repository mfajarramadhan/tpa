<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Kelola User</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white shadow-sm rounded-2xl">

            <h2 class="mb-4 text-lg font-semibold">Edit User</h2>

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="text-sm">Nama</label>
                    <input type="text" name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full p-2 border rounded">
                </div>

                @if(!$user->student)
                <div class="mb-3">
                    <label>Alamat</label>
                    <input type="text" name="address"
                        value="{{ old('address', $user->address) }}"
                        class="w-full p-2 border rounded">
                </div>
                @endif

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="text-sm">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full p-2 border rounded">
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="text" name="password"
                        class="w-full p-2 border rounded">

                    <p class="text-xs text-gray-500">
                        Kosongkan jika tidak ingin mengubah password
                    </p>
                </div>

                @if($user->student)

                <hr class="my-4">

                <h3 class="mb-2 font-semibold">Data Siswa</h3>

                {{-- KELAS --}}
                <div class="mb-3">
                    <label>Kelas</label>
                    <select name="classroom_id" class="w-full p-2 border rounded">
                        @foreach($classrooms as $class)
                            <option value="{{ $class->id }}"
                                {{ $user->student->classroom_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NIK --}}
                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" name="nik"
                        value="{{ old('nik', $user->student->nik) }}"
                        class="w-full p-2 border rounded">
                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="birth_date"
                        value="{{ old('birth_date', $user->student->birth_date) }}"
                        class="w-full p-2 border rounded">
                </div>

                {{-- GENDER --}}
                <div class="mb-3">
                    <label>Gender</label>
                    <select name="gender" class="w-full p-2 border rounded">
                        <option value="L" {{ $user->student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $user->student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- ASAL SEKOLAH --}}
                <div class="mb-3">
                    <label>Asal Sekolah</label>
                    <input type="text" name="school_origin"
                        value="{{ old('school_origin', $user->student->school_origin) }}"
                        class="w-full p-2 border rounded">
                </div>

            @endif

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Update
                </button>

            </form>
        </div>
    </div>

</x-app-layout>