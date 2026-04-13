<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard Siswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl">

            <h3 class="mb-4 font-semibold">Tugas</h3>

            @foreach($assignments as $task)
                <div class="p-4 mb-3 bg-white rounded shadow">
                    <p class="font-bold">{{ $task->title }}</p>
                    <p class="text-sm text-gray-500">Deadline: {{ $task->deadline }}</p>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>