<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Rekap Absensi - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto">

        {{-- SUMMARY --}}
        <div class="grid grid-cols-3 gap-4 mb-4">

            <div class="p-4 text-white bg-green-500 rounded">
                Hadir: {{ $hadir }}
            </div>

            <div class="p-4 text-white bg-yellow-500 rounded">
                Izin: {{ $izin }}
            </div>

            <div class="p-4 text-white bg-red-500 rounded">
                Alpha: {{ $alpha }}
            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded shadow">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($attendances as $a)
                        <tr class="border-t">
                            <td class="p-3">{{ $a->date }}</td>
                            <td class="p-3">{{ $a->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>