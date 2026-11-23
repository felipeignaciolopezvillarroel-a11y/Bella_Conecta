@extends('layouts.main')

@section('title', 'Nuestro equipo — Bella Conecta')

@section('content')
<section class="py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Hero --}}
        <div class="mb-12 sm:mb-16 max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-5">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                Nuestro equipo
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-[#1F1B1A] leading-[1.05] mb-5">
                Conoce a nuestro <span class="text-pink-600">equipo</span>
            </h1>

            <p class="text-base sm:text-lg text-gray-600 max-w-2xl leading-relaxed">
                Estilistas, peluqueros y coloristas apasionados por realzar tu belleza.
                Cada uno con su estilo y su especialidad.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            @forelse($team as $member)
            <x-team-card
                :employee-id="$member->id"
                :name="$member->name"
                :role="$member->role ?? 'Profesional'"
                :image="$member->photo_url"
                :bio="$member->bio" />
            @empty
            <p class="col-span-full text-center text-gray-500 py-12">
                No hay profesionales disponibles por ahora.
            </p>
            @endforelse
        </div>

    </div>
</section>
@endsection