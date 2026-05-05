<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Monitoring Tugas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto">

            <form method="GET" class="flex flex-wrap gap-2 mb-4">

                {{-- SEARCH --}}
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari siswa..."
                    class="px-3 py-2 text-sm border rounded-lg">

                {{-- FILTER STATUS --}}
                <select name="status" class="px-3 py-2 text-sm border rounded-lg">

                    <option value="">Semua Status</option>

                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>
                        Belum Submit
                    </option>

                    <option value="terkirim" {{ request('status') == 'terkirim' ? 'selected' : '' }}>
                        Terkirim
                    </option>

                    <option value="perbaiki" {{ request('status') == 'perbaiki' ? 'selected' : '' }}>
                        Perbaiki
                    </option>

                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>

                {{-- BUTTON --}}
                <button class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                    Filter
                </button>

                {{-- RESET --}}
                <a href="{{ route('materials.submissions', $material->id) }}"
                class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                    Reset
                </a>

            </form>

            {{-- INFO MATERI --}}
            <div class="p-4 mb-4 bg-white rounded shadow">
                <h3 class="font-bold">
                    {{ $material->title }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ $material->subject->classroom->name }}
                </p>
            </div>

            @php
                $total = $students->count();
                $submitted = $submissions->count();
            @endphp

                <div class="p-4 mb-4 bg-white rounded shadow">
                    <div class="flex justify-between text-sm">

                        <div>
                            Total Siswa: <strong>{{ $total }}</strong>
                        </div>

                        <div class="text-green-600">
                            Sudah Submit: {{ $submitted }}
                        </div>

                        <div class="text-red-500">
                            Belum Submit: {{ $total - $submitted }}
                        </div>

                    </div>
                </div>

            {{-- TABLE --}}
            <div class="overflow-hidden bg-white rounded shadow">
                
                @php
                $statusMap = [
                    'terkirim' => ['label' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-700'],
                    'perbaiki' => ['label' => 'Perbaiki', 'color' => 'bg-red-100 text-red-700'],
                    'selesai'  => ['label' => 'Selesai', 'color' => 'bg-green-100 text-green-700'],
                ];
                @endphp
                
                <table class="w-full text-sm">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Nama Siswa</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">File</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($students as $student)

                            @php
                                $submission = $submissions[$student->id] ?? null;
                            @endphp

                                <tr class="transition border-t hover:bg-gray-50">

                                {{-- NAMA --}}
                                <td class="p-3 font-semibold text-[var(--text-main)]">
                                    {{ $student->name }}
                                </td>

                                {{-- STATUS --}}
                                <td class="p-3 text-center">

                                    @if($submission)

                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($submission->status == 'selesai') bg-green-100 text-green-700
                                            @elseif($submission->status == 'perbaiki') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700
                                            @endif
                                        ">
                                            {{ ucfirst($submission->status) }}
                                        </span>

                                    @else

                                        <span class="px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded">
                                            Belum Submit
                                        </span>

                                    @endif

                                </td>

                                <td class="p-3 text-center">

                                    @if($submission)

                                        <div class="flex justify-center">

                                            {{-- FILE --}}
                                            @if($submission->file_path)

                                                @php
                                                    $fileUrl = asset('storage/' . $submission->file_path);
                                                    $ext = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION));
                                                @endphp

                                                <div class="flex items-center justify-center w-12 h-12 transition bg-gray-100 rounded cursor-pointer hover:scale-105"
                                                    onclick="window.open('{{ $fileUrl }}')">

                                                    @if(in_array($ext, ['jpg','jpeg','png']))
                                                        <img src="{{ $fileUrl }}" class="object-cover w-full h-full rounded">
                                                    @elseif($ext == 'pdf')
                                                        <span class="text-xs font-bold text-red-500">PDF</span>
                                                    @else
                                                        <span class="text-xs">FILE</span>
                                                    @endif

                                                </div>

                                            {{-- LINK --}}
                                            @elseif($submission->link)

                                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded cursor-pointer hover:scale-105"
                                                    onclick="window.open('{{ $submission->link }}')">

                                                    🔗

                                                </div>

                                            @endif

                                        </div>

                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td class="p-3 text-center">

                                    @if($submission)

                                        <div class="flex justify-center gap-2">

                                            {{-- SELESAI --}}
                                            @if($submission->status !== 'selesai')
                                            <form method="POST"
                                                action="{{ route('submissions.complete', $submission->id) }}">
                                                @csrf
                                                <button class="px-3 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">
                                                    Selesai
                                                </button>
                                            </form>
                                            @endif

                                            {{-- PERBAIKI --}}
                                            @if($submission->status !== 'perbaiki')
                                            <form method="POST"
                                                action="{{ route('submissions.revise', $submission->id) }}">
                                                @csrf
                                                <button class="px-3 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600">
                                                    Perbaiki
                                                </button>
                                            </form>
                                            @endif

                                        </div>

                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>
    </div>
</x-app-layout>