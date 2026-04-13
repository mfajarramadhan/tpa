<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Superadmin</h2>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 gap-6 mx-auto max-w-7xl md:grid-cols-4">

            <div class="p-6 bg-white rounded-lg shadow">
                <p class="text-gray-500">Total Siswa</p>
                <h2 class="text-2xl font-bold">{{ $totalStudents }}</h2>
            </div>

            <div class="p-6 bg-white rounded-lg shadow">
                <p class="text-gray-500">Total Guru</p>
                <h2 class="text-2xl font-bold">{{ $totalTeachers }}</h2>
            </div>

            <div class="p-6 bg-yellow-100 rounded-lg shadow">
                <p class="text-gray-500">Pending Siswa</p>
                <h2 class="text-2xl font-bold">{{ $pendingStudents }}</h2>
            </div>

            <div class="p-6 bg-red-100 rounded-lg shadow">
                <p class="text-gray-500">Pembayaran Pending</p>
                <h2 class="text-2xl font-bold">{{ $pendingPayments }}</h2>
            </div>

        </div>
    </div>
</x-app-layout>