@extends('layouts.main')

@section('title', 'Servicios — Bella Conecta')

@section('content')
<section class="relative py-10 sm:py-14 bg-[#FDF8F5] min-h-screen overflow-hidden">
    {{-- Decoración de fondo --}}
    <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-pink-200/40 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -top-16 -right-32 w-80 h-80 bg-rose-100/60 rounded-full blur-3xl"></div>

    <div class="relative w-full mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Hero --}}
        <div class="mb-12 sm:mb-16 max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-5">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                Todos nuestros servicios
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-[#1F1B1A] leading-[1.05] mb-5">
                Servicios de <span class="text-pink-600">belleza</span> pensados para ti
            </h1>

            <p class="text-base sm:text-lg text-gray-600 max-w-2xl leading-relaxed">
                Tratamientos profesionales para cada estilo y tipo de cabello.
                Reserva en segundos y descubre lo que podemos hacer por ti.
            </p>
        </div>

        {{-- Grid de servicios --}}
        <div id="servicios" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            @forelse($services as $service)
                <x-service-card
                    :icon="$service->icon"
                    :title="$service->name"
                    :slug="$service->slug"
                    :description="$service->description ?? ''"
                    :price="'$' . number_format($service->price, 0, ',', '.')"
                    :popular="$service->featured"
                    :items="$service->features ?? []"
                />
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-pink-100 text-pink-500 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-600 font-medium">No hay servicios disponibles por ahora.</p>
                    <p class="text-sm text-gray-400 mt-1">Vuelve pronto, estamos preparando algo nuevo.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>
@endsection