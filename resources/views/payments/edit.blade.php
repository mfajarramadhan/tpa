<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Nominal Iuran</h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">
        <div class="p-6 bg-white rounded shadow">

            <form method="POST" action="{{ route('payments.update', $payment->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label>Nominal</label>
                    <input type="number" name="original_amount"
                           value="{{ $payment->original_amount }}"
                           class="w-full p-2 border rounded">
                </div>

                <button class="px-4 py-2 text-white bg-blue-600 rounded">
                    Update
                </button>
            </form>

        </div>
    </div>
</x-app-layout>