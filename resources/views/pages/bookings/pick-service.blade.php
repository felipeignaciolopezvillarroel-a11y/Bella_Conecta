@extends('layouts.main')

@section('title', 'Reservar — Bella Conecta')

@section('content')
<section class="py-8 sm:py-12 lg:py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Header --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-6 mb-8 sm:mb-12">
            <div class="max-w-2xl text-left">
                <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-[11px] sm:text-xs font-semibold tracking-widest uppercase px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full mb-3 sm:mb-4">
                    <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                    Paso 1 de 2
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1F1B1A] leading-[1.12] sm:leading-[1.1] mb-2.5 sm:mb-3">
                    @if(isset($employee) && $employee)
                    Agenda tu cita con {{ $employee->name }}
                    @else
                    ¿Qué servicio te gustaría reservar?
                    @endif
                </h1>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    @if(isset($employee) && $employee)
                    Selecciona el tratamiento que deseas y revisa sus horarios disponibles.
                    @else
                    Selecciona el tratamiento que deseas para ver los horarios disponibles.
                    @endif
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            @forelse($services as $service)
            @php
            $routeParams = $service->slug;
            if (isset($employee) && $employee) {
                $routeParams = ['service' => $service->slug, 'employee' => $employee->uuid];
            }
            @endphp

            <a href="{{ route('bookings.create', $routeParams) }}"
                class="group bg-white border border-[#EEE3DD] rounded-2xl p-5 sm:p-6 hover:shadow-xl hover:border-pink-200 transition-all duration-300 hover:-translate-y-1 flex flex-col">

                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-2xl sm:text-3xl group-hover:scale-110 transition-transform shrink-0">
                        {{ $service->icon }}
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-pink-600 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>

                <h3 class="text-lg sm:text-xl font-bold text-[#1F1B1A] mb-1.5 sm:mb-2 group-hover:text-pink-600 transition">
                    {{ $service->name }}
                </h3>

                @if($service->description)
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-4 flex-1">
                    {{ $service->description }}
                </p>
                @endif

                <div class="flex items-center justify-between pt-4 border-t border-[#EEE3DD] text-xs sm:text-sm">
                    <span class="text-gray-500">
                        ⏱ {{ $service->duration_minutes }} min
                    </span>
                    <span class="text-pink-600 font-bold text-base sm:text-lg">
                        ${{ number_format($service->price, 0, ',', '.') }}
                    </span>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12 sm:py-16 px-4 bg-white border border-dashed border-[#EEE3DD] rounded-3xl">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl sm:text-2xl">🛠️</span>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-[#1F1B1A] mb-2">Este profesional no tiene servicios asignados</h3>
                <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6">
                    Por favor, selecciona otro profesional o elige un servicio desde el catálogo general.
                </p>
                <a href="{{ route('bookings.pick') }}" class="inline-block w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold px-6 py-3 rounded-full transition">
                    Ver todos los servicios
                </a>
            </div>
            @endforelse
        </div>

        {{-- Info extra --}}
        <div class="mt-10 sm:mt-12 bg-white border border-[#EEE3DD] rounded-2xl p-5 sm:p-8 max-w-3xl mx-auto text-center">
            <div class="flex items-center justify-center gap-2 text-pink-600 mb-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold text-xs sm:text-sm">¿No estás segura?</span>
            </div>
            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                Reserva cualquier servicio y en el salón podemos ajustarlo a lo que necesites. También puedes escribirnos a
                <a href="mailto:hola@bellaconecta.cl" class="text-pink-600 hover:underline break-all">hola@bellaconecta.cl</a>
            </p>
        </div>

    </div>
</section>
@endsection