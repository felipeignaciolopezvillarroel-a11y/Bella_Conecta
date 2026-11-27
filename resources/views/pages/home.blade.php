@extends('layouts.main')

@section('title', 'Bellas Conecta — Estilistas & Belleza')

@section('content')

{{-- HERO --}}
<section id="inicio" class="relative overflow-hidden bg-[#1F1B1A] min-h-screen flex items-center">

    {{-- CONTENEDOR DE FONDO --}}
    <div class="absolute inset-0 z-0 overflow-hidden">

        {{-- Imagen como fondo --}}
        <div
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/hero.jpg') }}');"
            aria-hidden="true">
        </div>

        {{-- Overlays (con un pequeño margen negativo para evitar líneas) --}}
        <div class="absolute -inset-1 backdrop-blur-[3px]"></div>
        <div class="absolute -inset-1 bg-black/45"></div>

        {{-- Gradiente diagonal (sin mix-blend para evitar bordes raros) --}}
        <div class="absolute -inset-1 bg-gradient-to-br from-pink-900/40 via-fuchsia-900/30 to-purple-900/40"></div>

        {{-- Gradiente lateral --}}
        <div class="absolute -inset-1 bg-gradient-to-r from-black/70 via-black/40 to-transparent lg:to-black/20"></div>

        {{-- Gradiente inferior --}}
        <div class="absolute -inset-x-1 -bottom-1 h-40 bg-gradient-to-b from-transparent to-white"></div>
    </div>

    {{-- CONTENIDO DEL HERO --}}

    <div class="relative z-10 w-full py-20 lg:py-28">
        <div class="w-full max-w-screen-2xl mx-auto px-6 md:px-12 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                {{-- COLUMNA IZQUIERDA --}}
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <h1 class="font-black text-white text-4xl sm:text-5xl md:text-6xl xl:text-7xl leading-[1.05] tracking-tight drop-shadow-[0_2px_12px_rgba(0,0,0,0.55)]">
                        Realza tu
                        <span class="relative inline-block">
                            <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-pink-300 via-pink-400 to-fuchsia-300">belleza</span>
                            <svg class="absolute -bottom-2 left-0 w-full h-3 text-pink-300/70" viewBox="0 0 100 12" preserveAspectRatio="none">
                                <path d="M2 8 Q 50 2 98 8" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" />
                            </svg>
                        </span>
                        <br> con nosotros.
                    </h1>

                    <p class="text-base sm:text-lg text-white/95 leading-relaxed max-w-xl mx-auto lg:mx-0 drop-shadow-[0_1px_4px_rgba(0,0,0,0.7)]">
                        Cortes, color, peinados y tratamientos personalizados. Un equipo de estilistas apasionados por hacerte sentir única. Reserva tu cita en segundos.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('bookings.pick') }}" class="group inline-flex items-center justify-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-8 py-4 rounded-full transition shadow-xl shadow-pink-900/50 hover:shadow-pink-600/60">
                            <span>Reservar ahora</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex flex-wrap justify-center lg:justify-start gap-x-6 gap-y-3 pt-4 text-sm text-white/95">
                        <span class="flex items-center gap-2 drop-shadow-[0_1px_3px_rgba(0,0,0,0.6)]">
                            <span class="w-5 h-5 rounded-full bg-pink-500/80 text-white flex items-center justify-center text-xs font-bold">✓</span>
                            Productos premium
                        </span>
                        <span class="flex items-center gap-2 drop-shadow-[0_1px_3px_rgba(0,0,0,0.6)]">
                            <span class="w-5 h-5 rounded-full bg-pink-500/80 text-white flex items-center justify-center text-xs font-bold">✓</span>
                            Reservas online
                        </span>
                        <span class="flex items-center gap-2 drop-shadow-[0_1px_3px_rgba(0,0,0,0.6)]">
                            <span class="w-5 h-5 rounded-full bg-pink-500/80 text-white flex items-center justify-center text-xs font-bold">✓</span>
                            Estilistas certificadas
                        </span>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: TARJETA --}}
                <div class="lg:col-span-5">
                    <div class="relative max-w-md mx-auto lg:mx-0 lg:ml-auto">
                        <div class="absolute -inset-4 bg-gradient-to-r from-pink-500 to-fuchsia-500 rounded-[2rem] blur-2xl opacity-40"></div>

                        <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/40 p-8 space-y-6">
                            <div class="text-center">
                                <div class="text-3xl mb-2">💇‍♀️</div>
                                <h3 class="text-xl font-bold text-[#1F1B1A]">Reserva en 30 segundos</h3>
                                <p class="text-sm text-gray-600 mt-1">Elige tu servicio y estilista favorita</p>
                            </div>

                            <div class="space-y-3">
                                @forelse($quickServices as $qs)
                                <a href="{{ route('bookings.create', $qs->slug) }}"
                                    class="relative flex items-center justify-between gap-3 p-4 bg-[#FDF8F5] hover:bg-pink-50 border border-[#EEE3DD] hover:border-pink-200 rounded-2xl transition group">

                                    @if($qs->featured)
                                    <span class="absolute -top-2 -right-2 bg-pink-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                                        Popular
                                    </span>
                                    @endif

                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-pink-100 text-xl shrink-0">
                                            {{ $qs->icon }}
                                        </span>
                                        <div class="text-left">
                                            <p class="font-semibold text-sm text-[#1F1B1A]">{{ $qs->name }}</p>
                                            <p class="text-xs text-gray-500">Desde ${{ number_format($qs->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <svg class="w-4 h-4 text-pink-600 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                @empty
                                <p class="text-center text-sm text-gray-500 py-4">Aún no hay servicios disponibles.</p>
                                @endforelse
                            </div>

                            <a href="{{ route('services.index') }}"
                                class="group block w-full text-center bg-[#1F1B1A] hover:bg-black text-white font-semibold py-3.5 rounded-full transition">
                                Ver todos los servicios
                            </a>

                            <p class="text-center text-xs text-gray-500">
                                Se aclara que este sitio es exclusivamente de prueba y las acciones realizadas en él no constituyen movimientos reales; todos los productos, servicios y profesionales mostrados corresponden a datos ficticios.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- SERVICIOS DESTACADOS (solo 3) --}}
<section id="servicios" class="py-16 sm:py-20 bg-white">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10 sm:mb-14">
            <div>
                <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                    <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                    Los más solicitados
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F1B1A] mb-2">
                    Servicios destacados
                </h2>
                <p class="text-gray-600 max-w-xl text-sm sm:text-base">
                    Tratamientos pensados para cada estilo y tipo de cabello.
                </p>
            </div>

            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 bg-white border border-[#EEE3DD] hover:border-pink-400 hover:text-pink-600 text-[#1F1B1A] font-semibold px-6 py-3 rounded-full transition self-start sm:self-auto shrink-0">
                Ver todos los servicios
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 items-stretch">
            @php
            // Traemos solo los 3 servicios destacados de la base de datos
            $serviciosDestacados = \App\Models\Service::where('active', true)
            ->where('featured', true)
            ->take(3)
            ->get();
            @endphp

            @forelse ($serviciosDestacados as $s)
            <x-service-card
                :icon="$s->icon"
                :title="$s->name"
                :slug="$s->slug"
                :description="$s->description"
                :price="'$' . number_format($s->price, 0, ',', '.')"
                :popular="$s->featured"
                :items="$s->features ?? []" />
            @empty
            {{-- Mensaje si no hay servicios destacados --}}
            <div class="col-span-full py-16 bg-[#FDF8F5] border-2 border-dashed border-[#EEE3DD] rounded-3xl flex flex-col items-center justify-center text-center px-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-6">
                    <span class="text-4xl text-gray-300">✨</span>
                </div>
                <h3 class="text-xl font-bold text-[#1F1B1A] mb-2">Próximamente nuevos servicios</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    Estamos preparando nuevas experiencias para ti. Por ahora, puedes revisar todos nuestros servicios disponibles.
                </p>
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition shadow-md">
                    Ver catálogo completo
                </a>
            </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ESTILISTAS (carrusel + botón "Ver todas") --}}
<section id="estilistas" class="py-16 sm:py-20 bg-[#FDF8F5]">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="flex items-end justify-between gap-4 mb-10 sm:mb-14 flex-wrap">
            <div class="flex-1 min-w-0">
                <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                    <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                    Mejor calificadas
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F1B1A] mb-2">Nuestro equipo</h2>
                <p class="text-gray-600 max-w-xl text-sm sm:text-base">Profesionales con años de experiencia y pasión por la belleza.</p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('team.index') }}" class="hidden sm:inline-flex items-center gap-2 bg-white border border-[#EEE3DD] hover:border-pink-400 hover:text-pink-600 text-[#1F1B1A] font-semibold px-5 py-2.5 rounded-full transition text-sm">
                    Ver todas
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>

                <button onclick="carruselEstilistas.prev()" aria-label="Anterior" class="cursor-pointer w-11 h-11 rounded-full bg-white border border-[#EEE3DD] hover:border-pink-400 hover:text-pink-600 text-gray-700 flex items-center justify-center transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button onclick="carruselEstilistas.next()" aria-label="Siguiente" class="cursor-pointer w-11 h-11 rounded-full bg-white border border-[#EEE3DD] hover:border-pink-400 hover:text-pink-600 text-gray-700 flex items-center justify-center transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="estilistas-wrapper" class="relative overflow-hidden"
            onmouseenter="carruselEstilistas.pause()"
            onmouseleave="carruselEstilistas.resume()">

            <div id="estilistas-track" class="flex gap-6 w-max will-change-transform py-6">
                @forelse ($team as $member)
                <div class="shrink-0 w-[260px] sm:w-[280px] md:w-[300px]" data-card>
                    <x-team-card
                        :employee-id="$member->id"
                        :uuid="$member->uuid"
                        :name="$member->name"
                        :role="$member->role ?? 'Profesional'"
                        :image="$member->photo_url" />
                </div>
                @empty
                <div class="w-full text-center text-gray-500 py-8">
                    Aún no hay profesionales disponibles.
                </div>
                @endforelse
            </div>

            <div class="pointer-events-none absolute top-0 left-0 bottom-0 w-12 sm:w-24 bg-gradient-to-r from-[#FDF8F5] via-[#FDF8F5]/80 to-transparent z-10"></div>
            <div class="pointer-events-none absolute top-0 right-0 bottom-0 w-12 sm:w-24 bg-gradient-to-l from-[#FDF8F5] via-[#FDF8F5]/80 to-transparent z-10"></div>
        </div>

        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('team.index') }}" class="inline-flex items-center gap-2 bg-white border border-[#EEE3DD] hover:border-pink-400 hover:text-pink-600 text-[#1F1B1A] font-semibold px-5 py-2.5 rounded-full transition text-sm">
                Ver todas las estilistas
            </a>
        </div>
    </div>
</section>

{{-- Script carrusel --}}
@push('scripts')
<script>
    const carruselEstilistas = (() => {
        const track = document.getElementById('estilistas-track');
        if (!track) return {
            next() {},
            prev() {},
            pause() {},
            resume() {}
        };

        const originalCards = Array.from(track.querySelectorAll('[data-card]'));
        const cardCount = originalCards.length;

        if (cardCount === 0) {
            return {
                next() {},
                prev() {},
                pause() {},
                resume() {}
            };
        }

        const wrapper = track.parentElement;
        const wrapperWidth = wrapper.offsetWidth;
        const cardWidth = originalCards[0].offsetWidth + 24; // 24px de gap
        const cardsVisible = Math.floor(wrapperWidth / cardWidth);

        // Si hay pocas tarjetas, centrar y no animar
        if (cardCount <= cardsVisible) {
            track.classList.remove('w-max');
            track.classList.add('w-full', 'justify-center');
            return {
                next() {},
                prev() {},
                pause() {},
                resume() {}
            };
        }

        // Si hay suficientes, duplicar y animar
        const cloneContainer = document.createElement('div');
        cloneContainer.className = 'flex gap-6';
        originalCards.forEach(card => {
            const clone = card.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            clone.removeAttribute('data-card');
            cloneContainer.appendChild(clone);
        });
        track.appendChild(cloneContainer);

        let offset = 0;
        const speed = 0.6;
        let isHovering = false;
        let isJumping = false;
        let isTabHidden = false;

        let blockWidth = track.scrollWidth / 2;
        const recalcBlockWidth = () => {
            blockWidth = track.scrollWidth / 2;
        };
        window.addEventListener('resize', () => {
            clearTimeout(window.__carruselResizeTimer);
            window.__carruselResizeTimer = setTimeout(recalcBlockWidth, 200);
        });

        const applyTransform = () => {
            const displayed = ((offset % blockWidth) + blockWidth) % blockWidth;
            track.style.transform = `translate3d(${-displayed}px, 0, 0)`;
        };

        const animate = () => {
            if (!isHovering && !isJumping && !isTabHidden) {
                offset += speed;
                applyTransform();
            }
            requestAnimationFrame(animate);
        };

        const pause = () => {
            isHovering = true;
        };
        const resume = () => {
            isHovering = false;
        };

        const jump = (direction) => {
            if (isJumping) return;
            const card = track.children[0];
            const cardWidth = card ? card.offsetWidth + 24 : 320;
            isJumping = true;
            const startOffset = offset;
            const endOffset = offset + direction * cardWidth;
            const duration = 400;
            const startTime = performance.now();
            const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);
            const step = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                offset = startOffset + (endOffset - startOffset) * easeOutCubic(progress);
                applyTransform();
                if (progress < 1) requestAnimationFrame(step);
                else {
                    offset = endOffset;
                    isJumping = false;
                }
            };
            requestAnimationFrame(step);
        };

        document.addEventListener('visibilitychange', () => {
            isTabHidden = document.hidden;
        });
        requestAnimationFrame(animate);

        return {
            next: () => jump(1),
            prev: () => jump(-1),
            pause,
            resume
        };
    })();
</script>
@endpush

{{-- PRODUCTOS TOP (solo 4) --}}
<section id="productos" class="py-16 sm:py-20 bg-white"
    x-data="cartApp()"
    x-init="
        isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        loginUrl = '{{ route('login') }}';
        hasClearCartFlag = {{ session()->has('clear_cart') ? 'true' : 'false' }};
    "
    x-cloak>

    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10 sm:mb-14">
            <div>
                <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                    <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                    Los más vendidos
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F1B1A] mb-2">
                    Nuestros productos top
                </h2>
                <p class="text-gray-600 max-w-xl text-sm sm:text-base">
                    Lleva a casa el cuidado profesional que usamos en el salón.
                </p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white border border-[#EEE3DD] hover:border-pink-400 hover:text-pink-600 text-[#1F1B1A] font-semibold px-6 py-3 rounded-full transition self-start sm:self-auto shrink-0">
                Ver todos los productos
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        {{-- Aquí llamamos al componente de Blade --}}
        @php
        $productosDestacados = \App\Models\Product::where('active', true)
        ->where('featured', true)
        ->take(4)
        ->get();
        @endphp

        <x-order-cards :products="$productosDestacados" grid-class="grid-cols-2 md:grid-cols-4" />

        {{-- Banner Inferior --}}
        <div class="mt-10 sm:mt-14 relative overflow-hidden bg-white border border-[#EEE3DD] rounded-2xl p-6 sm:p-8">
            <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-pink-100/60 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-48 h-48 rounded-full bg-fuchsia-100/60 blur-3xl"></div>

            <div class="relative flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 border border-pink-200 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="text-[#1F1B1A]">
                        <p class="font-semibold text-lg mb-1">Reserva online, paga y retira en tienda</p>
                        <p class="text-sm text-gray-600">Elige tus productos, genera tu ticket de retiro y paga al buscarlos en el salón. 📍 Av. Principal 123, Santiago</p>
                    </div>
                </div>

                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition shadow-md hover:shadow-pink-500/40 shrink-0">
                    Reservar productos
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="relative mt-6 pt-6 border-t border-[#EEE3DD] grid grid-cols-1 sm:grid-cols-3 gap-4 text-center sm:text-left">
                <div class="flex items-center gap-3 justify-center sm:justify-start">
                    <div class="w-9 h-9 rounded-full bg-pink-50 border border-pink-100 flex items-center justify-center shrink-0">
                        <span class="text-sm">🛍️</span>
                    </div>
                    <div>
                        <p class="text-[#1F1B1A] text-xs font-semibold">1. Elige tus productos</p>
                        <p class="text-gray-500 text-[11px]">Desde la web</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 justify-center sm:justify-start">
                    <div class="w-9 h-9 rounded-full bg-pink-50 border border-pink-100 flex items-center justify-center shrink-0">
                        <span class="text-sm">🎟️</span>
                    </div>
                    <div>
                        <p class="text-[#1F1B1A] text-xs font-semibold">2. Genera tu voleta</p>
                        <p class="text-gray-500 text-[11px]">Con tus datos de retiro</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 justify-center sm:justify-start">
                    <div class="w-9 h-9 rounded-full bg-pink-50 border border-pink-100 flex items-center justify-center shrink-0">
                        <span class="text-sm">💳</span>
                    </div>
                    <div>
                        <p class="text-[#1F1B1A] text-xs font-semibold">3. Paga al retirar</p>
                        <p class="text-gray-500 text-[11px]">En la tienda física</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ CARRITO FLOTANTE Y MODAL ══════════ --}}
    <div x-show="cart.length > 0 && isAuthenticated" class="fixed bottom-6 right-6 z-40 print:hidden" x-transition>
        <button @click="openCheckout = true" class="cursor-pointer bg-pink-600 hover:bg-pink-700 text-white rounded-full p-4 shadow-xl flex items-center gap-3 transition-all hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="font-bold text-lg" x-text="cart.length"></span>
            <span class="font-semibold" x-text="'$' + formatPrice(cartTotal)"></span>
        </button>
    </div>

    {{-- Modal de Checkout --}}
    <div x-show="openCheckout && isAuthenticated" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" style="display: none;" x-transition>
        <div @click.outside="openCheckout = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">

            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-pink-50">
                <h3 class="text-xl font-bold text-[#1F1B1A]">Tu Carrito</h3>
                <button @click="openCheckout = false" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-100">
                        <img :src="item.image" class="w-16 h-16 object-contain bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-[#1F1B1A]" x-text="item.name"></p>
                            <p class="text-sm text-pink-600 font-bold" x-text="'$' + formatPrice(item.price)"></p>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                            <button @click="updateQty(item.id, -1)" class="w-6 h-6 flex items-center justify-center hover:bg-white rounded transition">-</button>
                            <span class="text-sm font-semibold w-4 text-center" x-text="item.quantity"></span>
                            <button @click="updateQty(item.id, 1)" class="w-6 h-6 flex items-center justify-center hover:bg-white rounded transition">+</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 font-semibold">Total a pagar:</span>
                    <span class="text-2xl font-bold text-[#1F1B1A]" x-text="'$' + formatPrice(cartTotal)"></span>
                </div>

                {{-- Formulario de la Boleta --}}
                <form action="{{ route('orders.store') }}" method="POST" @submit="isSubmitting = true">
                    @csrf
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">

                    {{-- Datos del usuario logueado --}}
                    @auth
                    <div class="bg-white border border-gray-200 rounded-xl p-3 mb-4">
                        <p class="text-xs text-gray-500 mb-1">Boleta a nombre de:</p>
                        <p class="text-sm font-semibold text-[#1F1B1A]">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    @endauth

                    <button type="submit"
                        :disabled="isSubmitting || cart.length === 0"
                        class="cursor-pointer w-full bg-pink-600 hover:bg-pink-700 disabled:bg-gray-400 text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2">
                        <span x-show="!isSubmitting">Generar Boleta de Retiro</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generando...
                        </span>
                    </button>
                    {{-- Botón para cancelar/vaciar el carrito --}}
                    <button type="button"
                        @click="if (typeof clearCart === 'function') { clearCart(); } else { cart = []; localStorage.removeItem('cart'); } openCheckout = false;"
                        :disabled="isSubmitting"
                        class="cursor-pointer w-full mt-2.5 bg-white hover:bg-red-50 text-red-600 border border-red-200 hover:border-red-300 font-semibold py-2.5 rounded-xl transition text-sm">
                        Cancelar carrito
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-3">Al generar la boleta, podrás ir a pagar y retirar a la tienda.</p>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- NOSOTROS (sin cambios) --}}
<section id="nosotros" class="relative pt-16 pb-16 sm:pt-20 sm:pb-20 bg-[#FDF8F5] overflow-hidden">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">
        <div class="flex flex-wrap items-center gap-y-12 lg:gap-y-0">
            <div class="w-full lg:w-5/12 px-4 lg:px-0">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-2xl rounded-2xl overflow-hidden">
                    <img alt="Interior del salón Bellas Conecta" src="https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?w=1000&q=80" class="w-full align-middle aspect-[4/3] object-cover">

                    <div class="relative bg-gradient-to-br from-pink-500 to-fuchsia-600 p-8 pt-10 text-white">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block h-14 -top-12 text-pink-500 fill-current">
                            <polygon points="-30,95 583,95 583,65"></polygon>
                        </svg>
                        <h4 class="text-2xl font-bold mb-3">Más que un salón, una experiencia</h4>
                        <p class="text-sm font-light text-white/95 leading-relaxed">
                            En Bellas Conecta creemos que cada persona es única. Te escuchamos, asesoramos y creamos un look que te represente, con productos de alta calidad y técnicas actualizadas.
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-7/12 px-4 lg:pl-16 xl:pl-24">
                <div class="grid sm:grid-cols-2 gap-8">
                    <div class="flex flex-col border border-[#EEE3DD] rounded-2xl p-6 bg-[#FDF8F5]">
                        <div class="inline-flex items-center justify-center w-14 h-14 mb-5 rounded-full bg-white shadow-lg text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h6 class="text-xl mb-2 font-bold text-[#1F1B1A]">Atención personalizada</h6>
                        <p class="text-sm text-gray-600 leading-relaxed">Cada clienta es única. Escuchamos tus necesidades y te asesoramos para lograr el look perfecto.</p>
                    </div>

                    <div class="flex flex-col border border-[#EEE3DD] rounded-2xl p-6 bg-[#FDF8F5]">
                        <div class="inline-flex items-center justify-center w-14 h-14 mb-5 rounded-full bg-white shadow-lg text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h6 class="text-xl mb-2 font-bold text-[#1F1B1A]">Productos premium</h6>
                        <p class="text-sm text-gray-600 leading-relaxed">Trabajamos con marcas profesionales de alta gama para cuidar tu cabello en cada tratamiento.</p>
                    </div>

                    <div class="flex flex-col border border-[#EEE3DD] rounded-2xl p-6 bg-[#FDF8F5]">
                        <div class="inline-flex items-center justify-center w-14 h-14 mb-5 rounded-full bg-white shadow-lg text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <h6 class="text-xl mb-2 font-bold text-[#1F1B1A]">Ambiente acogedor</h6>
                        <p class="text-sm text-gray-600 leading-relaxed">Un espacio cómodo y relajado donde puedes disfrutar tu momento de belleza sin apuros.</p>
                    </div>

                    <div class="flex flex-col border border-[#EEE3DD] rounded-2xl p-6 bg-[#FDF8F5]">
                        <div class="inline-flex items-center justify-center w-14 h-14 mb-5 rounded-full bg-white shadow-lg text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h6 class="text-xl mb-2 font-bold text-[#1F1B1A]">Reservas online</h6>
                        <p class="text-sm text-gray-600 leading-relaxed">Agenda tu cita en segundos, elige a tu estilista favorita y confirma sin llamadas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- RESEÑAS --}}
<section id="reseñas" class="py-16 sm:py-20 bg-white">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10 sm:mb-14">
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F1B1A] mb-2">Lo que dicen nuestras clientas</h2>
                <p class="text-gray-600 max-w-xl text-sm sm:text-base">Miles de personas confían en Bellas Conecta. Estas son algunas de sus historias.</p>
            </div>

            <div class="flex items-center gap-4 bg-[#FDF8F5] border border-[#EEE3DD] rounded-2xl px-5 py-3 self-start sm:self-auto shrink-0">
                <div class="text-center">
                    <p class="text-3xl font-bold text-[#1F1B1A] leading-none">4.9</p>
                    <div class="flex items-center gap-0.5 mt-1">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            @endfor
                    </div>
                </div>
                <div class="border-l border-[#EEE3DD] pl-4">
                    <p class="text-xs text-gray-500">Basado en</p>
                    <p class="text-sm font-semibold text-[#1F1B1A]">+250 reseñas</p>
                </div>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $resenas = [
            ['María José', 'Increíble atención. Salí sintiéndome renovada, el equipo es muy profesional y se nota que aman lo que hacen.', 5, 'Cliente frecuente', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80', 'Hace 2 semanas'],
            ['Carolina Rojas', 'El balayage que me hicieron fue espectacular. ¡100% recomendado! Me entendieron perfecto el estilo que buscaba.', 5, 'Primera visita', 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80', 'Hace 1 mes'],
            ['Fernanda López', 'Ambiente acogedor y precios justos. Ya tengo mi estilista de confianza y no pienso cambiarla.', 4, 'Cliente recurrente', 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=200&q=80', 'Hace 3 semanas'],
            ];
            @endphp

            @foreach ($resenas as $r)
            <x-review-card :name="$r[0]" :review="$r[1]" :rating="$r[2]" :role="$r[3]" :image="$r[4]" :date="$r[5]" />
            @endforeach
        </div>
    </div>
</section>

{{-- DEJAR RESEÑA (sin cambios) --}}
<section id="resenas-form" class="py-16 sm:py-20 bg-[#FDF8F5]">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#1F1B1A] leading-tight mb-6">
                    Cuéntanos tu
                    <span class="relative inline-block">
                        <span class="relative text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-fuchsia-500">experiencia</span>
                        <svg class="absolute -bottom-2 left-0 w-full h-3 text-pink-300" viewBox="0 0 100 12" preserveAspectRatio="none">
                            <path d="M2 8 Q 50 2 98 8" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" />
                        </svg>
                    </span>
                </h2>

                <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-8">
                    Tu opinión nos ayuda a mejorar y a que otras personas conozcan Bellas Conecta. Cuéntanos cómo fue tu visita, qué te gustó y qué podríamos hacer aún mejor.
                </p>

                <ul class="space-y-4 text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                        <div>
                            <p class="font-semibold">Reseña visible en la web</p>
                            <p class="text-sm text-gray-500">Tu testimonio aparecerá en nuestra sección de clientas</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                        <div>
                            <p class="font-semibold">Nos ayuda a mejorar</p>
                            <p class="text-sm text-gray-500">Leemos todas las reseñas con atención</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                        <div>
                            <p class="font-semibold">Solo toma 1 minuto</p>
                            <p class="text-sm text-gray-500">Sin registro, sin complicaciones</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-r from-pink-300 to-fuchsia-300 rounded-[2rem] blur-2xl opacity-30"></div>

                <div class="relative bg-white rounded-3xl shadow-2xl border border-pink-100 p-6 sm:p-8">
                    <form action="#" method="POST" class="space-y-5">
                        @csrf

                        <div x-data="{ rating: 0, hovered: 0 }">
                            <label class="block text-sm font-semibold text-[#1F1B1A] mb-3">
                                ¿Cómo calificarías tu experiencia? <span class="text-pink-600">*</span>
                            </label>
                            <div class="flex items-center gap-1">
                                <template x-for="star in 5" :key="star">
                                    <button type="button"
                                        @click="rating = star"
                                        @mouseenter="hovered = star"
                                        @mouseleave="hovered = 0"
                                        class="transition-transform hover:scale-110 focus:outline-none">
                                        <svg class="w-9 h-9 transition-colors" :class="(hovered || rating) >= star ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                </template>
                                <span class="ml-3 text-sm text-gray-500" x-text="rating > 0 ? rating + '/5' : 'Selecciona'"></span>
                            </div>
                            <input type="hidden" name="rating" :value="rating" required>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#1F1B1A] mb-2">Tu nombre <span class="text-pink-600">*</span></label>
                            <input type="text" id="name" name="name" required placeholder="Ej: María José" class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
                        </div>

                        <div>
                            <label for="service" class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                                ¿Qué servicio recibiste? <span class="text-gray-400 font-normal">(opcional)</span>
                            </label>
                            <select id="service" name="service" class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm bg-white">
                                <option value="">Selecciona un servicio</option>
                                <option value="corte">Corte de cabello</option>
                                <option value="coloracion">Coloración</option>
                                <option value="manicure">Manicure & Pedicure</option>
                                <option value="tratamiento">Tratamiento capilar</option>
                                <option value="maquillaje">Maquillaje</option>
                                <option value="novia">Peinado de novia</option>
                            </select>
                        </div>

                        <div>
                            <label for="review" class="block text-sm font-semibold text-[#1F1B1A] mb-2">Tu reseña <span class="text-pink-600">*</span></label>
                            <textarea id="review" name="review" rows="4" required maxlength="500" placeholder="Cuéntanos cómo fue tu experiencia, qué te gustó más, etc." class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-pink-600 to-fuchsia-600 hover:from-pink-700 hover:to-fuchsia-700 text-white font-semibold py-3.5 rounded-full transition shadow-lg hover:shadow-pink-500/50 inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Enviar reseña
                        </button>

                        <p class="text-center text-xs text-gray-500">✨ Tu reseña será revisada antes de publicarse</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA FINAL (sin cambios) --}}
<section id="reservar" class="py-16 sm:py-20 bg-gradient-to-br from-pink-500 to-fuchsia-600 text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">¿Lista para tu transformación?</h2>
        <p class="text-white/90 mb-8 text-base sm:text-lg">Reserva tu cita ahora y vive la experiencia Bellas Conecta.</p>
        <a href="{{ route('bookings.pick') }}" class="inline-block bg-white text-pink-600 font-semibold px-8 py-3.5 rounded-full hover:bg-pink-50 transition shadow-lg">
            Reservar mi cita
        </a>
    </div>
</section>

@endsection