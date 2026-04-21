<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Rekap Absensi - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto">

            <div class="overflow-x-auto card-panel">
                <table class="w-full text-sm table-custom">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Sesi</th>
                            <th>Status</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                        <tr>
                            <td>{{ $att->date }}</td>
                            <td>{{ ucfirst($att->session) }}</td>
                            <td>
                                <span class="badge 
                                    {{ $att->status == 'hadir' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($att->status) }}
                                </span>
                            </td>
                            <td>{{ $att->note ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-small">
                                Belum ada data absensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>