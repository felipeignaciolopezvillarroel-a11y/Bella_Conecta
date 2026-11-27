@props(['name', 'review', 'rating' => 5, 'role' => null, 'image' => null, 'date' => null])

<div class="group relative bg-white rounded-3xl border border-[#EEE3DD] p-6 sm:p-8 pt-10 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full mt-6">

    {{-- Icono flotante superior (mitad fuera, mitad dentro) --}}
    <div class="absolute -top-6 left-1/2 -translate-x-1/2">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-pink-50 border border-pink-200 shadow-md">
            <div class="w-4 h-4 rounded-full bg-pink-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-2.5 h-2.5 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <span class="text-xs font-semibold text-pink-700 whitespace-nowrap">
                {{ $role ?? 'Cliente' }}
            </span>
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="flex flex-col flex-1 text-left pt-2">

        {{-- Encabezado: Nombre + Tiempo --}}
        <div class="flex items-center justify-between gap-3 pb-4 mb-4 border-b border-[#EEE3DD]">
            <h3 class="text-base sm:text-lg font-bold text-[#1F1B1A] leading-tight truncate flex-1 min-w-0">
                {{ $name }}
            </h3>
            @if($date)
            <span class="shrink-0 text-[10px] sm:text-xs font-medium text-gray-400 bg-[#FDF8F5] px-2 py-1 rounded-full border border-[#EEE3DD]">
                {{ $date }}
            </span>
            @endif
        </div>

        {{-- Texto de la reseña --}}
        <p class="text-sm text-gray-600 leading-relaxed flex-1 line-clamp-5">
            "{{ $review }}"
        </p>
    </div>

    {{-- Estrellas flotantes inferiores (mitad fuera, mitad dentro) --}}
    <div class="absolute -bottom-5 left-1/2 -translate-x-1/2">
        <div class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-white border border-[#EEE3DD] shadow-md">
            @for ($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                @endfor
        </div>
    </div>
</div>