<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Upload Tugas
        </h2>
    </x-slot>

    <div class="max-w-xl py-6 mx-auto">

        <form method="POST"
              action="{{ route('submissions.store') }}"
              enctype="multipart/form-data"
              class="p-5 bg-white rounded shadow">

            @csrf

            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

            <input type="file" name="file" required
                   class="w-full p-2 mb-3 border rounded">

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Upload
            </button>

        </form>

    </div>
</x-app-layout>