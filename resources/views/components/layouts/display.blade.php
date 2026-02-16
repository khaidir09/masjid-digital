<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masjid Digital Display' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Work+Sans:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .font-arab { family: 'Amiri', serif; }
        .font-sans { family: 'Work Sans', sans-serif; }
        /* Sembunyikan kursor mouse saat idle */
        body { cursor: none; }
    </style>
</head>
<body class="bg-black text-white overflow-hidden w-screen h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
