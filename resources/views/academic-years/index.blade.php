<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Tahun Akademik
        </h2>
    </x-slot>

    <div class="max-w-5xl py-6 mx-auto space-y-5">

        {{-- FORM --}}
        <div class="p-5 bg-white shadow-sm rounded-xl">

            <form method="POST"
                  action="{{ route('academic-years.store') }}">

                @csrf

                <div>

                    <div class="flex gap-3">

                        <input type="text"
                            name="name"
                            value="{{ old('name') }}"
                            maxlength="9"
                            inputmode="numeric"
                            pattern="[0-9/]*"
                            oninput="this.value = this.value.replace(/[^0-9/]/g, '')"
                            placeholder="Contoh: 2025/2026"
                            class="w-full border-gray-300 rounded-lg">

                        <button class="btn-primary">
                            Tambah
                        </button>

                    </div>

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </form>

        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden bg-white shadow-sm rounded-xl">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-sm text-gray-600">

                        <th class="p-3 text-left">
                            Tahun
                        </th>

                        <th class="p-3 text-center">
                            Status
                        </th>

                        <th class="p-3 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($years as $year)

                    <tr class="border-t">

                        <td class="p-3">
                            {{ $year->name }}
                        </td>

                        <td class="p-3 text-center">

                            @if($year->is_active)

                                <span class="px-2 py-1 text-xs text-white bg-green-600 rounded">

                                    Aktif

                                </span>

                            @else

                                <span class="px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">

                                    Nonaktif

                                </span>

                            @endif

                        </td>

                        <td class="p-3">

                            <div class="flex justify-center gap-2">

                                {{-- SET ACTIVE --}}
                                @if(!$year->is_active)

                                <form method="POST"
                                      action="{{ route('academic-years.setActive', $year->id) }}">

                                    @csrf

                                    <button class="px-3 py-1 text-xs text-white bg-blue-600 rounded">

                                        Set Aktif

                                    </button>

                                </form>

                                @endif

                                {{-- DELETE --}}
                                @if(!$year->is_active)

                                <form method="POST"
                                      action="{{ route('academic-years.destroy', $year->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button class="px-3 py-1 text-xs text-white bg-red-500 rounded">

                                        Hapus

                                    </button>

                                </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>