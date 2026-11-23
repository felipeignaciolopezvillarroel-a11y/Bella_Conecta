<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin') — Bella Conecta</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDF8F5] text-[#2B2B2B] antialiased h-screen flex overflow-hidden">

    {{-- Menú Lateral --}}
    @include('partials.admin.sidebar')

    {{-- Contenido Principal --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        {{-- Barra Superior --}}
        @include('partials.admin.topbar')

        {{-- Contenido Dinámico (Dashboard, Servicios, etc) --}}
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </main>
        
    </div>

    {{-- Toasts (Notificaciones) --}}
    <x-toast />
    @stack('scripts')
</body>
</html>