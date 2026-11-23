<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal del trabajador') — Bella Conecta</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDF8F5] text-[#2B2B2B] antialiased h-screen flex overflow-hidden">

    {{-- Sidebar del portal --}}
    @include('partials.portal.sidebar')

    {{-- Contenido principal --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- Topbar --}}
        @include('partials.portal.topbar')

        {{-- Contenido dinámico --}}
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </main>

    </div>

    <x-toast />
    @stack('scripts')
</body>
</html>