<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Guru</h2>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 gap-6 mx-auto max-w-7xl md:grid-cols-3">

            <div class="p-6 bg-white rounded-lg shadow">
                <p>Total Siswa</p>
                <h2 class="text-2xl font-bold">{{ $totalStudents }}</h2>
            </div>

            <div class="p-6 bg-green-100 rounded-lg shadow">
                <p>Absensi Hari Ini</p>
                <h2 class="text-2xl font-bold">{{ $todayAttendance }}</h2>
            </div>

            <div class="p-6 bg-blue-100 rounded-lg shadow">
                <p>Total Tugas</p>
                <h2 class="text-2xl font-bold">{{ $assignments }}</h2>
            </div>

        </div>
    </div>
</x-app-layout>