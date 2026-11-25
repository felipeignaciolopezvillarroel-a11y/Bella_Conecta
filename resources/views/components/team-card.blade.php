@props([
    'name',
    'role',
    'image' => null,
    'instagram' => null,
    'employeeId' => null,
    'uuid' => null,
])

@php
    $imageUrl = null;

    if ($image) {
        if (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://', 'data:image/', '/storage/'])) {
            $imageUrl = $image;
        } else {
            $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($image);
        }
    }

    $trimmedName = trim($name);
    $firstName = mb_strstr($trimmedName, ' ', true) ?: $trimmedName;
    $initial = mb_strtoupper(mb_substr($trimmedName, 0, 1));
    
    // Preferimos el UUID si está disponible, si no, el ID
    $bookingParam = $uuid ?? $employeeId;
    $bookingUrl = $bookingParam
        ? route('bookings.pick', ['employee' => $bookingParam])
        : route('bookings.pick');
@endphp

<div class="group relative bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-[#EEE3DD] flex flex-col h-full">

    {{-- Imagen con overlay y badge --}}
    <div class="relative aspect-[4/5] overflow-hidden bg-pink-50 shrink-0">
        @if($imageUrl)
            <img
                src="{{ $imageUrl }}"
                alt="{{ $name }}"
                loading="lazy"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
        @else
            <div class="w-full h-full flex items-center justify-center text-6xl font-bold text-pink-300">
                {{ $initial }}
            </div>
        @endif

        {{-- Degradado inferior para legibilidad --}}
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        {{-- Icono de Instagram --}}
        @if($instagram)
            @php
                $cleanIg = ltrim(preg_replace('#^https?://(www\.)?instagram\.com/#i', '', trim($instagram)), '@/');
            @endphp
            <a href="https://instagram.com/{{ $cleanIg }}" target="_blank" rel="noopener"
               class="absolute top-3 right-3 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/90 backdrop-blur text-pink-600 hover:bg-white transition opacity-0 group-hover:opacity-100 shadow-sm z-10">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
            </a>
        @endif
    </div>

    {{-- Contenido --}}
    <div class="p-5 sm:p-6 text-center flex flex-col flex-1 justify-between">
        <div>
            <h3 class="font-bold text-lg text-[#1F1B1A] group-hover:text-pink-600 transition-colors">
                {{ $name }}
            </h3>

            <div class="mt-2 flex justify-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-pink-50 text-xs font-semibold uppercase tracking-wider text-pink-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500 animate-pulse"></span>
                    {{ $role }}
                </span>
            </div>
        </div>

        {{-- Botón de reservar --}}
        <div class="mt-6">
            <a href="{{ $bookingUrl }}"
               class="w-full inline-flex items-center justify-center gap-2 bg-[#FDF8F5] hover:bg-pink-600 border border-[#EEE3DD] hover:border-pink-600 text-[#1F1B1A] hover:text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 group/cta">
                Reservar con {{ $firstName }}
                <svg class="w-4 h-4 transition-transform group-hover/cta:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>