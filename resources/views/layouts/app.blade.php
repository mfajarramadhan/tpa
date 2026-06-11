<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SPMB') }}</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Iconify & Chart --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="flex h-screen overflow-x-hidden text-body">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- OVERLAY --}}
    <div id="mobile-overlay"
         class="fixed inset-0 hidden bg-black bg-opacity-40 md:hidden"
         onclick="toggleSidebar()"></div>

    {{-- MAIN --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[var(--bg)] overflow-x-hidden">

        {{-- HEADER --}}
        @include('layouts.header')

        {{-- CONTENT --}}
        <div class="flex-1 p-5 overflow-x-hidden overflow-y-auto sm:p-8">
            {{ $slot }}
        </div>

        {{-- FOOTER --}}
        <footer class="px-5 py-2 border-t bg-[var(--surface)] border-[var(--border)]">

            <div class="flex items-center justify-end gap-2"> 

                <div class="leading-tight text-right">

                    <div class="text-xs font-medium text-[var(--text-tertiary)]">
                        Horizon University Indonesia
                    </div>

                    <div class="text-[10px] text-[var(--text-tertiary)] opacity-70">
                        © 2026 All Rights Reserved
                    </div>

                </div>
                
                <img
                    src="{{ asset('storage/logo/logo-horizon.jpeg') }}"
                    alt="Horizon University Indonesia"
                    class="object-contain w-7 h-7">

            </div>

        </footer>

    </main>

</body>

<script>

    function confirmAction(
        event,
        title = 'Yakin?',
        text = 'Aksi ini tidak dapat dibatalkan',
        confirmText = 'Ya',
        icon = 'warning'
    ) {

        event.preventDefault();

        Swal.fire({

            title: title,

            text: text,

            icon: icon,

            showCancelButton: true,

            confirmButtonText: confirmText,

            cancelButtonText: 'Batal',

            reverseButtons: true

        }).then((result) => {

            if (result.isConfirmed) {

                event.target.submit();

            }

        });
    }

</script>
</html>

