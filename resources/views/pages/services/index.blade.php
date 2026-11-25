@extends('layouts.main')

@section('title', 'Servicios — Bella Conecta')

@section('content')
<section class="relative py-8 sm:py-12 lg:py-16 bg-[#FDF8F5] min-h-screen overflow-hidden">
    {{-- Decoración de fondo --}}
    <div class="pointer-events-none absolute -top-24 -left-24 w-64 h-64 sm:w-96 sm:h-96 bg-pink-200/40 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -top-16 -right-32 w-60 h-60 sm:w-80 sm:h-80 bg-rose-100/60 rounded-full blur-3xl"></div>

    <div class="relative w-full mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Hero --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-6 mb-8 sm:mb-12 lg:mb-16">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-[#1F1B1A] leading-[1.1] sm:leading-[1.05] mb-3 sm:mb-5">
                    Servicios de <span class="text-pink-600">belleza</span> pensados para ti
                </h1>

                <p class="text-sm sm:text-base lg:text-lg text-gray-600 leading-relaxed">
                    Tratamientos profesionales para cada estilo y tipo de cabello.
                    Reserva en segundos y descubre lo que podemos hacer por ti.
                </p>
            </div>

            <a href="{{ route('home') }}"
               class="self-start shrink-0 group inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-pink-600 bg-white hover:bg-pink-50/70 border border-[#EEE3DD] hover:border-pink-200 px-3.5 py-2 rounded-full shadow-xs transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Volver</span>
            </a>
        </div>

        {{-- Grid de servicios --}}
        <div id="servicios" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            @forelse($services as $service)
            <x-service-card
                :icon="$service->icon"
                :title="$service->name"
                :slug="$service->slug"
                :description="$service->description ?? ''"
                :price="'$' . number_format($service->price, 0, ',', '.')"
                :popular="$service->featured"
                :items="$service->features ?? []" />
            @empty
            <div class="col-span-full text-center py-12 sm:py-16 px-4">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-pink-100 text-pink-500 mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm sm:text-base text-gray-600 font-medium">No hay servicios disponibles por ahora.</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Vuelve pronto, estamos preparando algo nuevo.</p>
            </div>
            @endforelse
        </div>

    </div>
</section>
@endsection