<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Orang Tua</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            <h3 class="mb-4 font-semibold">Daftar Anak</h3>

            <div class="grid gap-4 md:grid-cols-2">
                @foreach($students as $student)
                    <div class="p-4 bg-white rounded shadow">
                        <p class="font-bold">{{ $student->name }}</p>
                        <p class="text-sm text-gray-500">{{ $student->classroom->name ?? '-' }}</p>
                    </div>
                @endforeach
            </div>

            <h3 class="mt-6 mb-4 font-semibold">Status Iuran</h3>

            @foreach($payments as $payment)
                <div class="flex justify-between p-4 mb-2 bg-white rounded shadow">
                    <span>{{ $payment->month }}</span>
                    <span class="font-semibold">{{ $payment->status }}</span>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>