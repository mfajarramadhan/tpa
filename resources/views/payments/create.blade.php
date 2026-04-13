<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Upload Bukti Pembayaran</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('payments.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- PILIH ANAK --}}
                <div class="mb-4">
                    <label class="block mb-1">Pilih Anak</label>
                    <select name="student_id" class="w-full p-2 border rounded">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BULAN --}}
                <div class="mb-4">
                    <label>Bulan</label>
                    <input type="month"
                           name="month"
                           class="w-full p-2 border rounded">
                </div>

                {{-- NOMINAL --}}
                <div class="mb-4">
                    <label>Nominal</label>
                    <input type="number"
                           name="amount"
                           class="w-full p-2 border rounded"
                           placeholder="Contoh: 100000">
                </div>

                {{-- FILE --}}
                <div class="mb-4">
                    <label>Upload Bukti</label>
                    <input type="file"
                           name="proof_file"
                           class="w-full p-2 border rounded">
                </div>

                {{-- SUBMIT --}}
                <button
                    onclick="this.disabled=true;this.form.submit();"
                    class="px-4 py-2 text-white transition bg-blue-600 rounded hover:bg-blue-700">
                    Upload
                </button>

            </form>

        </div>
    </div>
</x-app-layout>