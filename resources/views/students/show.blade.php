<x-app-layout>

    <div class="max-w-2xl p-6 mx-auto mt-6 bg-white rounded shadow">

        <h2 class="mb-4 text-lg font-semibold">Detail Siswa</h2>

        {{-- DATA SISWA --}}
        <div class="space-y-2 text-sm">

            <p><strong>Nama:</strong> {{ $student->name }}</p>

            <p><strong>Email:</strong>
                {{ $student->user->email ?? '-' }}
            </p>

            <p><strong>Kelas:</strong>
                {{ $student->classroom->name ?? '-' }}
            </p>

            <p><strong>Status:</strong>
                <span class="px-2 py-1 text-white rounded
                    {{ $student->status == 'aktif' ? 'bg-green-500' : 'bg-gray-500' }}">
                    {{ $student->status }}
                </span>
            </p>

            <p><strong>NISN:</strong> {{ $student->nisn ?? '-' }}</p>

            <p><strong>Tanggal Lahir:</strong>
                {{ $student->birth_date
                    ? \Carbon\Carbon::parse($student->birth_date)->format('d M Y')
                    : '-' }}
            </p>

            <p><strong>Gender:</strong>
                @if($student->gender == 'L')
                    Laki-laki
                @elseif($student->gender == 'P')
                    Perempuan
                @else
                    -
                @endif
            </p>

            <p><strong>Asal Sekolah:</strong>
                {{ $student->school_origin ?? '-' }}
            </p>

        </div>

        {{-- BUTTON --}}
        <div class="mt-4">
            <a href="{{ route('students.index') }}"
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded">
                Kembali
            </a>
        </div>

    </div>

</x-app-layout>