@props(['products', 'gridClass' => 'grid-cols-2 md:grid-cols-4'])

<div class="grid {{ $gridClass }} gap-3 sm:gap-4">
    @forelse($products as $p)
    <div class="bg-white border border-[#EEE3DD] rounded-xl overflow-hidden group hover:shadow-lg transition flex flex-col relative">
        
        {{-- Badge de Agotado --}}
        @if($p->stock <= 0)
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-20 flex items-center justify-center">
                <span class="bg-gray-800 text-white text-xs font-bold px-3 py-1.5 rounded-full transform -rotate-12">
                    Agotado
                </span>
            </div>
        @endif

        <div class="relative aspect-square bg-gray-50 p-3">
            @if($p->discount_price)
            <span class="absolute top-1.5 left-1.5 bg-pink-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full z-10">Oferta</span>
            @endif
            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-contain group-hover:scale-105 transition duration-300">
        </div>
        <div class="p-3 flex flex-col flex-1">
            <p class="text-[10px] text-pink-600 font-semibold mb-0.5">{{ $p->brand }}</p>
            <h3 class="text-xs font-semibold text-[#1F1B1A] line-clamp-2 mb-2 flex-1" title="{{ $p->name }}">{{ $p->name }}</h3>
            <div class="flex items-end justify-between mb-2">
                <div>
                    @if($p->discount_price)
                    <p class="text-[10px] text-gray-400 line-through">${{ number_format($p->price, 0, ',', '.') }}</p>
                    <p class="text-sm font-bold text-[#1F1B1A]">${{ number_format($p->discount_price, 0, ',', '.') }}</p>
                    @else
                    <p class="text-sm font-bold text-[#1F1B1A]">${{ number_format($p->price, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
            <button 
                @click="addToCart({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->discount_price ?? $p->price }}, '{{ $p->image_url }}', {{ $p->stock }})"
                class="cursor-pointer w-full bg-pink-50 hover:bg-pink-600 text-pink-600 hover:text-white border border-pink-200 font-semibold py-1.5 rounded-lg transition text-xs flex items-center justify-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed"
                {{ $p->stock <= 0 ? 'disabled' : '' }}>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                {{ $p->stock <= 0 ? 'Agotado' : 'Agregar' }}
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center text-gray-500 py-8">
        Próximamente nuevos productos.
    </div>
    @endforelse
</div>