@props(['icon', 'title', 'description', 'price', 'popular' => false, 'items' => [], 'slug' => null])

<div class="group relative w-full">
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-b from-pink-200 via-pink-100 to-fuchsia-200 p-[1px] shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-pink-300/50">

        <div class="relative rounded-2xl bg-gradient-to-b from-white to-[#FDF8F5] p-6">

            {{-- Glows decorativos suaves --}}
            <div class="absolute -left-16 -top-16 h-32 w-32 rounded-full bg-gradient-to-br from-pink-300/40 to-fuchsia-300/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-80"></div>
            <div class="absolute -bottom-16 -right-16 h-32 w-32 rounded-full bg-gradient-to-br from-fuchsia-300/40 to-pink-300/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-80"></div>

            {{-- Cinta POPULAR --}}
            @if($popular)
            <div class="absolute -right-[1px] -top-[1px] overflow-hidden rounded-tr-2xl z-10">
                <div class="absolute h-20 w-20 bg-gradient-to-r from-pink-400 to-fuchsia-400"></div>
                <div class="absolute h-20 w-20 bg-white/95"></div>
                <div class="absolute right-0 top-[22px] h-[2px] w-[56px] rotate-45 bg-gradient-to-r from-pink-400 to-fuchsia-400"></div>
                <span class="absolute right-1 top-1 text-[10px] font-semibold text-pink-600">POPULAR</span>
            </div>
            @endif

            {{-- Icono + título --}}
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">{{ $icon }}</span>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-pink-500">{{ $title }}</h3>
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-[#1F1B1A]">{{ $price }}</span>
                </div>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    {{ $description }}
                </p>
            </div>

            {{-- Lista de features --}}
            @if(count($items) > 0)
            <div class="relative mt-6 space-y-4">
                @foreach($items as $item)
                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-pink-100">
                        <svg stroke="currentColor" viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-pink-500">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1F1B1A]">{{ $item['title'] }}</p>
                        @if(!empty($item['subtitle']))
                        <p class="text-xs text-gray-500">{{ $item['subtitle'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Botón con gradiente --}}
            <div class="relative mt-8">
                <a href="{{ $slug ? route('bookings.create', $slug) : '#reservar' }}"
                    class="group/btn relative block w-full overflow-hidden rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-500 p-px font-semibold text-white shadow-md transition-shadow hover:shadow-lg hover:shadow-pink-300/50">
                    <div class="relative rounded-xl bg-pink-500 px-4 py-3 text-center transition-colors group-hover/btn:bg-transparent">
                        <span class="relative flex items-center justify-center gap-2">
                            Reservar
                            <svg stroke="currentColor" viewBox="0 0 24 24" fill="none" class="h-4 w-4 transition-transform duration-300 group-hover/btn:translate-x-1">
                                <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"></path>
                            </svg>
                        </span>
                    </div>
                </a>
            </div>

            {{-- Footer de la tarjeta --}}
            <div class="mt-4 flex items-center justify-center gap-2">
                <svg stroke="currentColor" viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-gray-400">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"></path>
                </svg>
                <span class="text-xs font-medium text-gray-500">Sin costo de reserva</span>
            </div>
        </div>
    </div>
</div>