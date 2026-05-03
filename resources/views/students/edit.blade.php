<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Anak</h2>
    </x-slot>

    <div class="py-6">
        <div class="p-6 mx-auto max-w-7xl card-panel">

            <form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div class="mb-4">
                    <label>Nama</label>
                    <input type="text" name="name"
                        value="{{ $student->name }}"
                        class="w-full p-2 border rounded">
                </div>

                {{-- TANGGAL LAHIR --}}
                <div class="mb-4">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="birth_date"
                        value="{{ $student->birth_date }}"
                        max="{{ now()->subYears(4)->format('Y-m-d') }}"
                        class="w-full p-2 border rounded">
                </div>

                {{-- SEKOLAH --}}
                <div class="mb-4">
                    <label>Sekolah Asal</label>
                    <input type="text" name="school_origin"
                        value="{{ $student->school_origin }}"
                        class="w-full p-2 border rounded">
                </div>

                {{-- KK --}}
                <div class="mb-4">
                    <label>KK</label>

                    @if($student->kk_file)
                        <img src="{{ asset('storage/'.$student->kk_file) }}"
                            class="w-32 mb-2 border rounded">
                    @endif

                    <input type="file" name="kk_file"
                        onchange="previewImage(event, 'preview_kk')"
                        class="w-full p-2 border rounded">

                    <img id="preview_kk" class="hidden w-32 mt-2 border rounded">
                </div>

                {{-- AKTA --}}
                <div class="mb-4">
                    <label>Akta</label>

                    @if($student->birth_certificate_file)
                        <img src="{{ asset('storage/'.$student->birth_certificate_file) }}"
                            class="w-32 mb-2 border rounded">
                    @endif

                    <input type="file" name="birth_certificate_file"
                        onchange="previewImage(event, 'preview_akta')"
                        class="w-full p-2 border rounded">

                    <img id="preview_akta" class="hidden w-32 mt-2 border rounded">
                </div>

                <button class="px-4 py-2 text-white bg-yellow-500 rounded">
                    Update
                </button>
            </form>

        </div>
    </div>
</x-app-layout>

<script>
    function previewImage(event, id) {
        const input = event.target;
        const preview = document.getElementById(id);

        if (input.files && input.files[0]) {
            preview.src = URL.createObjectURL(input.files[0]);
            preview.classList.remove('hidden');
        }
    }
</script>