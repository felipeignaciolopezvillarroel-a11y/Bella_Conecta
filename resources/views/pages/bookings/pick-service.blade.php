@extends('layouts.main')

@section('title', 'Reservar — Bella Conecta')

@section('content')
<section class="py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                Paso 1 de 2
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#1F1B1A] mb-3">
                ¿Qué servicio quieres reservar?
            </h1>
            <p class="text-gray-600 max-w-xl mx-auto">
                Elige el servicio que necesitas y luego te mostraremos los horarios disponibles.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            @foreach($services as $service)
                <a href="{{ route('bookings.create', $service->slug) }}"
                   class="group bg-white border border-[#EEE3DD] rounded-2xl p-6 hover:shadow-xl hover:border-pink-200 transition-all duration-300 hover:-translate-y-1 flex flex-col">

                    <div class="flex items-start justify-between mb-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                            {{ $service->icon }}
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-pink-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-[#1F1B1A] mb-2 group-hover:text-pink-600 transition">
                        {{ $service->name }}
                    </h3>

                    @if($service->description)
                        <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">
                            {{ $service->description }}
                        </p>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-[#EEE3DD] text-sm">
                        <span class="text-gray-500">
                            ⏱ {{ $service->duration_minutes }} min
                        </span>
                        <span class="text-pink-600 font-bold text-lg">
                            ${{ number_format($service->price, 0, ',', '.') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Info extra --}}
        <div class="mt-12 bg-white border border-[#EEE3DD] rounded-2xl p-6 sm:p-8 max-w-3xl mx-auto text-center">
            <div class="flex items-center justify-center gap-2 text-pink-600 mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-semibold text-sm">¿No estás segura?</span>
            </div>
            <p class="text-sm text-gray-600">
                Reserva cualquier servicio y en el salón podemos ajustarlo a lo que necesites. También puedes escribirnos a
                <a href="mailto:hola@bellaconecta.cl" class="text-pink-600 hover:underline">hola@bellaconecta.cl</a>
            </p>
        </div>

    </div>
</section>
@endsection