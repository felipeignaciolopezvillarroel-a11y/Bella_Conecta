@extends('layouts.main')

@section('title', 'Nuestro equipo — Bellas Conecta')

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
                    Conoce a nuestro <span class="text-pink-600">equipo</span>
                </h1>

                <p class="text-sm sm:text-base lg:text-lg text-gray-600 leading-relaxed">
                    Estilistas, peluqueros y coloristas apasionados por realzar tu belleza.
                    Cada uno con su estilo y su especialidad.
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

        {{-- Grid --}}
        <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
            @forelse($team as $member)
            <x-team-card
                :employee-id="$member->id"
                :name="$member->name"
                :role="$member->role ?? 'Profesional'"
                :image="$member->photo_url"
                :bio="$member->bio" />
            @empty
            <p class="col-span-full text-center text-gray-500 py-12 text-sm sm:text-base">
                No hay profesionales disponibles por ahora.
            </p>
            @endforelse
        </div>

    </div>
</section>
@endsection