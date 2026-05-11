<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Approval Pendaftaran</h2>
    </x-slot>

    <div class="py-6 md:py-0">
        <div class="mx-auto max-w-7xl">

            <div class="relative">

                {{-- FLOATING ALERT WRAPPER --}}
                <div class="absolute top-0 left-0 z-50 w-full pointer-events-none">

                    {{-- SUCCESS --}}
                    @if(session('success'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 3000)"
                        @click.outside="show = false"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-3"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="pointer-events-auto flex items-center p-3 text-white rounded-xl shadow-md 
                            bg-gradient-to-t from-[var(--primary-dark)] to-[var(--primary)] 
                            bg-opacity-80 backdrop-blur-sm">

                        <div class="text-sm font-semibold ms-2">
                            {{ session('success') }}
                        </div>

                        <button @click="show = false"
                            class="flex items-center justify-center w-8 h-8 font-bold text-black transition rounded-md ms-auto bg-white/80 hover:bg-white">
                            ✕
                        </button>
                    </div>
                    @endif


                    {{-- ERROR --}}
                    @if(session('error'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 3000)"
                        @click.outside="show = false"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-3"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="pointer-events-auto flex items-center p-3 text-white rounded-xl shadow-md 
                            bg-gradient-to-t from-[var(--danger)] to-red-400 
                            bg-opacity-80 backdrop-blur-sm">

                        <div class="text-sm font-semibold ms-2">
                            {{ session('error') }}
                        </div>

                        <button @click="show = false"
                            class="flex items-center justify-center w-8 h-8 font-bold text-black transition rounded-md ms-auto bg-white/80 hover:bg-white">
                            ✕
                        </button>
                    </div>
                    @endif

                </div>

            </div>

            @forelse ($students as $student)
                @php
                    $payment = $student->payments->where('type', 'registration')->first();
                @endphp

                <div class="p-5 mx-auto mb-5 max-w-7xl card-panel">

                    {{-- TOP --}}
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                        {{-- LEFT --}}
                        <div class="flex-1 min-w-0">

                            {{-- HEADER --}}
                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <h3 class="text-xl font-bold text-slate-800">
                                        {{ $student->name }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Orang Tua: {{ $student->parent->name }}
                                    </p>

                                </div>

                                {{-- STATUS --}}
                                <div class="shrink-0">

                                    @if(!$payment || !$payment->proof_file)

                                        <span class="badge badge-warning">
                                            ⏳ Menunggu Pembayaran
                                        </span>

                                    @elseif($payment->status == 'pending')

                                        <span class="badge badge-warning">
                                            ⏳ Menunggu Verifikasi
                                        </span>

                                    @elseif($payment->status == 'paid')

                                        <span class="badge badge-success">
                                            ✔ Terverifikasi
                                        </span>

                                    @endif

                                </div>

                            </div>

                            {{-- MINI DETAIL --}}
                            <div class="grid grid-cols-2 gap-3 mt-5 text-sm md:grid-cols-4">

                                <div class="p-3 rounded-xl bg-gray-50">

                                    <p class="text-xs font-semibold text-slate-500">
                                        NISN
                                    </p>

                                    <p class="mt-1 font-bold text-slate-800">
                                        {{ $student->nisn }}
                                    </p>

                                </div>

                                <div class="p-3 rounded-xl bg-gray-50">

                                    <p class="text-xs font-semibold text-slate-500">
                                        Gender
                                    </p>

                                    <p class="mt-1 font-bold text-slate-800">

                                        @if($student->gender == 'L')
                                            Laki-laki
                                        @elseif($student->gender == 'P')
                                            Perempuan
                                        @else
                                            -
                                        @endif

                                    </p>

                                </div>

                                <div class="p-3 rounded-xl bg-gray-50">

                                    <p class="text-xs font-semibold text-slate-500">
                                        Tanggal Lahir
                                    </p>

                                    <p class="mt-1 font-bold text-slate-800">
                                        {{ $student->birth_date }}
                                    </p>

                                </div>

                                <div class="p-3 rounded-xl bg-gray-50">

                                    <p class="text-xs font-semibold text-slate-500">
                                        Sekolah
                                    </p>

                                    <p class="mt-1 font-bold truncate text-slate-800"
                                    title="{{ $student->school_origin }}">

                                        {{ $student->school_origin }}

                                    </p>

                                </div>

                            </div>

                            {{-- ALAMAT --}}
                            <div class="p-3 mt-3 rounded-xl bg-gray-50">

                                <p class="text-xs font-semibold text-slate-500">
                                    Alamat
                                </p>

                                <p class="mt-1 text-sm font-bold break-words text-slate-800">
                                    {{ $student->parent->address }}
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- DOKUMEN --}}
                    <div class="pt-5 mt-5 border-t border-gray-100">

                        {{-- FOTO 3 SEJAJAR --}}
                        <div class="grid grid-cols-3 gap-4">

                            
                            {{-- BUKTI BAYAR --}}
                            <div class="text-center">

                                <p class="mb-3 text-xs font-semibold text-slate-500">
                                    Bukti Bayar
                                </p>

                                @if($payment && $payment->proof_file)

                                    @php
                                        $paymentUrl = asset('storage/' . $payment->proof_file);
                                        $paymentExt = strtolower(pathinfo($payment->proof_file, PATHINFO_EXTENSION));
                                    @endphp

                                    {{-- IMAGE --}}
                                    @if(in_array($paymentExt, ['jpg', 'jpeg', 'png']))

                                        <img src="{{ $paymentUrl }}"
                                            onclick="openUploadPreview('image', '{{ $paymentUrl }}')"
                                            class="object-cover w-full max-w-[90px] md:max-w-[140px] h-20 md:h-28 mx-auto border rounded-xl cursor-pointer hover:shadow-md hover:scale-[1.02] transition">

                                    {{-- PDF --}}
                                    @elseif($paymentExt === 'pdf')

                                        <div onclick="openUploadPreview('pdf', '{{ $paymentUrl }}')"
                                            class="flex flex-col items-center justify-center w-full max-w-[90px] md:max-w-[140px] h-20 md:h-28 mx-auto transition bg-red-100 border cursor-pointer rounded-xl hover:shadow-md hover:scale-[1.02]">

                                            <div class="text-4xl">
                                                📄
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                @else

                                    <p class="text-xs text-gray-400">
                                        Belum upload
                                    </p>

                                @endif

                            </div>
                            
                            {{-- KK --}}
                            <div class="text-center">

                                <p class="mb-3 text-xs font-semibold text-slate-500">
                                    Kartu Keluarga
                                </p>

                                @if($student->kk_file)

                                    @php
                                        $kkUrl = asset('storage/' . $student->kk_file);
                                        $kkExt = strtolower(pathinfo($student->kk_file, PATHINFO_EXTENSION));
                                    @endphp

                                    {{-- IMAGE --}}
                                    @if(in_array($kkExt, ['jpg', 'jpeg', 'png']))

                                        <img src="{{ $kkUrl }}"
                                            onclick="openUploadPreview('image', '{{ $kkUrl }}')"
                                            class="object-cover w-full max-w-[90px] md:max-w-[140px] h-20 md:h-28 mx-auto border rounded-xl cursor-pointer hover:shadow-md hover:scale-[1.02] transition">

                                    {{-- PDF --}}
                                    @elseif($kkExt === 'pdf')

                                        <div onclick="openUploadPreview('pdf', '{{ $kkUrl }}')"
                                            class="flex flex-col items-center justify-center w-full max-w-[90px] md:max-w-[140px] h-20 md:h-28 mx-auto transition bg-red-100 border cursor-pointer rounded-xl hover:shadow-md hover:scale-[1.02]">

                                            <div class="text-4xl">
                                                📄
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                @else

                                    <p class="text-xs text-gray-400">
                                        Belum upload
                                    </p>

                                @endif

                            </div>

                            {{-- AKTA --}}
                            <div class="text-center">

                                <p class="mb-3 text-xs font-semibold text-slate-500">
                                    Akta Kelahiran
                                </p>

                                @if($student->birth_certificate_file)

                                    @php
                                        $aktaUrl = asset('storage/' . $student->birth_certificate_file);
                                        $aktaExt = strtolower(pathinfo($student->birth_certificate_file, PATHINFO_EXTENSION));
                                    @endphp

                                    {{-- IMAGE --}}
                                    @if(in_array($aktaExt, ['jpg', 'jpeg', 'png']))

                                        <img src="{{ $aktaUrl }}"
                                            onclick="openUploadPreview('image', '{{ $aktaUrl }}')"
                                            class="object-cover w-full max-w-[90px] md:max-w-[140px] h-20 md:h-28 mx-auto border rounded-xl cursor-pointer hover:shadow-md hover:scale-[1.02] transition">

                                    {{-- PDF --}}
                                    @elseif($aktaExt === 'pdf')

                                        <div onclick="openUploadPreview('pdf', '{{ $aktaUrl }}')"
                                            class="flex flex-col items-center justify-center w-full max-w-[90px] md:max-w-[140px] h-20 md:h-28 mx-auto transition bg-red-100 border cursor-pointer rounded-xl hover:shadow-md hover:scale-[1.02]">

                                            <div class="text-4xl">
                                                📄
                                            </div>

                                            <p class="mt-2 text-xs font-semibold text-red-500">
                                                PDF
                                            </p>

                                        </div>

                                    @endif

                                @else

                                    <p class="text-xs text-gray-400">
                                        Belum upload
                                    </p>

                                @endif

                            </div>

                        </div>

                    {{-- ACTION --}}
                    <div class="flex items-center justify-between pt-5 mt-5 border-t border-gray-100">

                        {{-- REJECT --}}
                        <form method="POST"
                            action="{{ route('approval.students.reject', $student->id) }}"
                            class="flex items-center gap-2"
                            onsubmit="return confirm('Yakin ingin menolak siswa ini?')">
                            @csrf

                            <div x-data="{ openReject: false }" class="flex items-center gap-2">

                            {{-- BUTTON OPEN MODAL --}}
                            <button @click="openReject = true"
                                    class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                                Reject
                            </button>

                            {{-- MODAL --}}
                            <div x-show="openReject"
                                x-transition
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">

                                <div @click.outside="openReject = false"
                                    class="w-full max-w-md p-5 bg-white shadow-xl rounded-xl">

                                    <h2 class="mb-3 text-lg font-semibold">Alasan Penolakan</h2>

                                    <form method="POST"
                                        action="{{ route('approval.students.reject', $student->id) }}">
                                        @csrf

                                        <textarea name="reject_reason"
                                                        rows="3"
                                                        class="input-solid"
                                                        placeholder="Tulis alasan..."
                                                        required></textarea>

                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button"
                                                    @click="openReject = false"
                                                    class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                                                Batal
                                            </button>

                                            <button class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                Kirim
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                        </form>

                        {{-- APPROVE --}}
                        <form method="POST"
                            action="{{ route('approval.students.approve', $student->id) }}"
                            class="flex items-center gap-2" 
                            onsubmit="return confirm('Yakin ingin menyetujui siswa ini?')">
                            @csrf

                            {{-- APPROVE MODAL --}}
                            <div x-data="{ openApprove: false }" class="flex items-center gap-2">

                                {{-- BUTTON --}}
                                @if($payment && $payment->proof_file)
                                    <button @click="openApprove = true"
                                            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                        Approve
                                    </button>
                                @else
                                    <button class="px-4 py-2 text-sm text-white bg-gray-400 rounded-lg cursor-not-allowed" disabled>
                                        Menunggu Pembayaran
                                    </button>
                                @endif

                                {{-- MODAL --}}
                                <div x-show="openApprove"
                                    x-transition
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">

                                    <div @click.outside="openApprove = false"
                                        class="w-full max-w-md p-5 bg-white shadow-xl rounded-xl">

                                        <h2 class="mb-3 text-lg font-semibold">Pilih Kelas</h2>

                                        <form method="POST"
                                            action="{{ route('approval.students.approve', $student->id) }}">
                                            @csrf

                                            <select name="classroom_id"
                                                    class="w-full p-2 text-sm border rounded-lg focus:ring focus:ring-blue-200"
                                                    required>
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($classrooms as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>

                                            <div class="flex justify-end gap-2 mt-4">
                                                <button type="button"
                                                        @click="openApprove = false"
                                                        class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                                                    Batal
                                                </button>

                                                <button class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                    Approve
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>
                        </form>
                        
                    </div>
                </div>
            @empty
                <div class="p-6 text-center bg-white shadow-sm rounded-2xl">
                    Tidak ada data approval
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= UPLOAD PREVIEW MODAL ================= --}}
    <div id="uploadPreviewModal"
        style="display:none"
        class="fixed inset-0 z-50 items-center justify-center bg-black/80 backdrop-blur-sm">

        {{-- CLOSE --}}
        <button onclick="closeUploadPreview()"
            class="absolute z-50 text-3xl text-white top-4 right-6 hover:text-red-400">
            ✕
        </button>

        <div class="w-full max-w-6xl px-4">

            {{-- IMAGE --}}
            <img id="uploadPreviewImage"
                class="hidden object-contain w-full max-h-[90vh] rounded-lg shadow-2xl">

            {{-- PDF --}}
            <iframe id="uploadPreviewPdf"
                class="hidden w-full bg-white rounded-lg h-[90vh] shadow-2xl">
            </iframe>

        </div>

    </div>
</x-app-layout>


<script>

    function previewFile(event, type) {

        const file = event.target.files[0];

        if (!file) return;

        const wrapper = document.getElementById(`preview_${type}_wrapper`);
        const image = document.getElementById(`preview_${type}`);
        const pdf = document.getElementById(`preview_${type}_pdf`);

        wrapper.classList.remove('hidden');

        const fileType = file.type;

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (fileType.startsWith('image/')) {

            image.src = URL.createObjectURL(file);
            image.classList.remove('hidden');

        }

        // PDF
        else if (fileType === 'application/pdf') {

            pdf.dataset.src = URL.createObjectURL(file);

            pdf.classList.remove('hidden');
            pdf.classList.add('flex');
        }
    }

    function openUploadPreview(type, src) {

        const modal = document.getElementById('uploadPreviewModal');

        const image = document.getElementById('uploadPreviewImage');
        const pdf = document.getElementById('uploadPreviewPdf');

        // reset
        image.classList.add('hidden');
        pdf.classList.add('hidden');

        // IMAGE
        if (type === 'image') {

            image.src = src;
            image.classList.remove('hidden');

        }

        // PDF
        if (type === 'pdf') {

            pdf.src = src;
            pdf.classList.remove('hidden');

        }

        modal.style.display = 'flex';
    }

    function closeUploadPreview() {

        const modal = document.getElementById('uploadPreviewModal');

        document.getElementById('uploadPreviewImage').src = '';
        document.getElementById('uploadPreviewPdf').src = '';

        modal.style.display = 'none';
    }

    // backdrop click
    document.getElementById('uploadPreviewModal')
        .addEventListener('click', function(e) {

            if (e.target.id === 'uploadPreviewModal') {
                closeUploadPreview();
            }

        });

</script>