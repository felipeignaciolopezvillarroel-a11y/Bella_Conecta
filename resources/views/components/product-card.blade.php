@props(['icon', 'name', 'price', 'rating' => 5, 'reviews' => 0, 'badge' => null])

<div class="group bg-white rounded-2xl shadow-md hover:shadow-xl border border-[#EEE3DD] overflow-hidden transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">

    {{-- Icono / "imagen" del producto --}}
    <a href="#" class="block relative bg-gradient-to-br from-pink-50 to-fuchsia-50 aspect-square flex items-center justify-center overflow-hidden">

        {{-- Badge --}}
        @if($badge)
            <span class="absolute top-3 left-3 z-10 bg-pink-600 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm">
                {{ $badge }}
            </span>
        @endif

        {{-- Glow decorativo --}}
        <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-pink-200/50 blur-2xl"></div>
        <div class="absolute -bottom-8 -left-8 w-24 h-24 rounded-full bg-fuchsia-200/50 blur-2xl"></div>

        <span class="relative text-7xl sm:text-8xl transition-transform duration-500 group-hover:scale-110">
            {{ $icon }}
        </span>
    </a>

    <div class="p-5 flex flex-col flex-1">

        {{-- Nombre --}}
        <a href="#">
            <h3 class="text-[#1F1B1A] font-semibold text-base sm:text-lg tracking-tight leading-tight mb-3 group-hover:text-pink-600 transition line-clamp-2">
                {{ $name }}
            </h3>
        </a>

        {{-- Rating --}}
        <div class="flex items-center mb-4">
            <div class="flex text-yellow-400">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 {{ $i < $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
            </div>
            <span class="bg-pink-100 text-pink-700 text-xs font-semibold ml-2 px-2.5 py-0.5 rounded">
                {{ number_format($rating, 1) }}
            </span>
            @if($reviews > 0)
                <span class="text-xs text-gray-500 ml-2">({{ $reviews }})</span>
            @endif
        </div>

        {{-- Precio + botón --}}
        <div class="mt-auto flex items-center justify-between gap-2">
            <span class="text-2xl sm:text-3xl font-bold text-[#1F1B1A]">{{ $price }}</span>
            <button class="inline-flex items-center gap-1.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-full text-xs sm:text-sm px-4 py-2.5 transition shadow-md hover:shadow-pink-500/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="hidden sm:inline">Agregar</span>
            </button>
        </div>
    </div>
</div>