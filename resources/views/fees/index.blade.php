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

                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan Biaya
                </button>
            </form>
        </div>

        {{-- ===================== --}}
        {{-- 🔴 SECTION: ADJUSTMENT --}}
        {{-- ===================== --}}
        <div class="p-5 bg-white rounded shadow">
            <h3 class="mb-4 font-semibold text-red-600">
                Penyesuaian Tagihan (Mass Update)
            </h3>

            <form method="POST" action="{{ route('fees.adjustment.apply') }}">
                @csrf

                <div class="mb-3">
                    <label class="text-sm">Nominal Penyesuaian</label>
                    <input type="number" name="amount"
                        class="w-full p-2 border rounded"
                        placeholder="contoh: -10000">

                    <p class="mt-1 text-xs text-gray-500">
                        Gunakan minus (-) untuk diskon
                    </p>

                    <p class="mt-1 text-xs text-red-500">
                        ⚠ Akan mengubah semua tagihan yang belum dibayar
                    </p>
                </div>

                <button class="px-4 py-2 text-white bg-red-600 rounded">
                    Terapkan Penyesuaian
                </button>
            </form>
        </div>

    </div>
</x-app-layout>