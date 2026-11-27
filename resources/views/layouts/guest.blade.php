<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bellas Conecta') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen grid md:grid-cols-2">

            {{-- COLUMNA IZQUIERDA: Imagen + branding --}}
            <div class="hidden md:flex flex-col justify-between bg-gradient-to-br from-pink-500 to-fuchsia-600 text-white p-12 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1200&q=80"
                         alt=""
                         class="w-full h-full object-cover" />
                </div>

                <a href="/" class="relative z-10">
                    <div class="flex flex-col leading-tight">
                        <span class="text-2xl font-light tracking-wide text-white">
                            bella
                            <span class="font-extrabold text-pink-200 tracking-wider">CONECTA</span>
                            <span class="text-pink-200 text-xl">♡</span>
                        </span>
                        <span class="text-xs text-pink-100 tracking-widest uppercase mt-1">
                            Tu belleza, tu estilo, tu elección
                        </span>
                    </div>
                </a>

                <div class="relative z-10">
                    <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">
                        Bienvenida a tu espacio de belleza
                    </h1>
                    <p class="text-white/90 text-lg leading-relaxed">
                        Reserva citas, gestiona tus servicios y conecta con las mejores estilistas.
                    </p>
                </div>

                <p class="relative z-10 text-xs text-white/70">
                    © {{ date('Y') }} Bellas Conecta
                </p>
            </div>

            {{-- COLUMNA DERECHA: Formulario --}}
            <div class="flex flex-col justify-center items-center px-6 py-12 bg-[#FDF8F5]">

                {{-- Logo visible solo en mobile --}}
                <div class="md:hidden mb-8">
                    <a href="/">
                        <x-logo size="md" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md">
                    <div class="bg-white shadow-xl rounded-3xl p-8 border border-[#EEE3DD]">
                        {{ $slot }}
                    </div>
                </div>

            </div>

        </div>
    </body>
</html>