<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CinemaVerse') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { background-color: #050509; color: white; }
        /* Scrollbar Merah Khas Cinema */
        .nice-scroll::-webkit-scrollbar { width: 6px; }
        .nice-scroll::-webkit-scrollbar-thumb { background: #dc2626; border-radius: 10px; }
    </style>
</head>
<body class="font-sans antialiased bg-[#050509] text-white">
    <div class="min-h-screen">
        @include('layouts.navigation')

        {{-- Gunakan w-full agar tidak kejepit di tengah --}}
        <main class="w-full pt-20">
            @yield('content')
        </main>
    </div>

    {{-- Masukkan modal di sini agar bisa dipanggil global --}}
    <x-modal-map />
    <x-modal-branch /> {{-- Modal untuk pilih lokasi --}}
</body>
</html>