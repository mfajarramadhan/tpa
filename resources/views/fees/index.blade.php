<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Pengaturan Biaya</h2>
    </x-slot>

    <div class="max-w-3xl py-6 mx-auto space-y-6">
        {{-- ALERT --}}
            @if(session('success'))
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                    {{ session('success') }}
                </div>
            @endif

        {{-- ===================== --}}
        {{-- 🟢 SECTION: FEE --}}
        {{-- ===================== --}}
        <div class="p-5 bg-white rounded shadow">
            <h3 class="mb-4 font-semibold">Biaya Default</h3>

            <form method="POST" action="{{ route('fees.update') }}">
                @csrf

                <div class="mb-3">
                    <label class="text-sm">Biaya Pendaftaran</label>
                    <input type="number" name="registration_fee"
                        value="{{ $fee->registration_fee }}"
                        class="w-full p-2 border rounded">
                        @if ($errors->any())
                            <div class="text-red-500">
                                {{ $errors->first() }}
                            </div>
                        @endif
                </div>

                <div class="mb-3">
                    <label class="text-sm">Iuran Bulanan</label>
                    <input type="number" name="monthly_fee"
                        value="{{ $fee->monthly_fee }}"
                        class="w-full p-2 border rounded">
                </div>

                <p class="text-xs text-gray-500">
                    Perubahan biaya hanya berlaku untuk pendaftaran baru & bulan berikutnya
                </p>

                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan Biaya
                </button>
            </form>

            <div class="mt-6">
                <h3 class="mb-2 font-semibold">Riwayat Perubahan Biaya</h3>

                <table class="w-full text-sm border">
                    <tr class="bg-gray-100">
                        <th class="p-2">User</th>
                        <th class="p-2">Pendaftaran</th>
                        <th class="p-2">Iuran</th>
                        <th class="p-2">Waktu</th>
                    </tr>

                    @foreach($logs as $log)
                        <tr class="border-t">
                            <td class="p-2">{{ $log->user->name }}</td>

                            <td class="p-2">
                                {{ number_format($log->old_registration_fee) }}
                                →
                                {{ number_format($log->new_registration_fee) }}
                            </td>

                            <td class="p-2">
                                {{ number_format($log->old_monthly_fee) }}
                                →
                                {{ number_format($log->new_monthly_fee) }}
                            </td>

                            <td class="p-2">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>
</x-app-layout>