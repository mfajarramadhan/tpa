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
</head>

<body class="flex h-screen overflow-x-hidden text-body">

    {{-- SIDEBAR --}}
    @include('layouts.navigation')

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

    </main>

</body>
</html>

