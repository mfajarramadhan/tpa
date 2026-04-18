<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Pengaturan Biaya</h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        <form method="POST" action="{{ route('fees.update') }}"
              class="p-5 bg-white rounded shadow">
            @csrf

            <div class="mb-4">
                <label class="block text-sm">Biaya Pendaftaran</label>
                <input type="number" name="registration_fee"
                       value="{{ $fee->registration_fee }}"
                       class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm">Iuran Bulanan</label>
                <input type="number" name="monthly_fee"
                       value="{{ $fee->monthly_fee }}"
                       class="w-full p-2 border rounded">
            </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Simpan
            </button>

        </form>

    </div>
</x-app-layout>