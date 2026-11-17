@props(['name', 'review', 'rating' => 5, 'role' => null, 'image' => null, 'date' => null])

<div class="relative flex flex-col bg-white rounded-2xl border border-[#EEE3DD] p-6 sm:p-8 shadow-sm hover:shadow-lg hover:border-pink-200 transition-all duration-300 h-full">

    {{-- Icono de comillas decorativo --}}
    <svg class="absolute top-6 right-6 w-10 h-10 text-pink-100" fill="currentColor" viewBox="0 0 32 32">
        <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/>
    </svg>

    {{-- Header: avatar + nombre + rol + rating --}}
    <div class="flex items-start gap-4 mb-5">

        {{-- Avatar --}}
        @if($image)
            <img src="{{ $image }}" alt="{{ $name }}" class="w-14 h-14 rounded-full object-cover object-center shrink-0 border-2 border-pink-100" />
        @else
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-400 to-fuchsia-500 flex items-center justify-center text-white font-bold text-lg shrink-0 border-2 border-pink-100">
                {{ strtoupper(substr($name, 0, 1)) }}
            </div>
        @endif

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h5 class="font-semibold text-lg text-[#1F1B1A] leading-tight truncate">
                        {{ $name }}
                    </h5>
                    @if($role)
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $role }}
                        </p>
                    @endif
                </div>

                {{-- Rating --}}
                <div class="flex items-center gap-0.5 shrink-0">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 {{ $i < $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Texto de la reseña --}}
    <p class="text-gray-700 leading-relaxed text-sm sm:text-base flex-1">
        "{{ $review }}"
    </p>

    {{-- Fecha --}}
    @if($date)
        <p class="mt-4 text-xs text-gray-400">
            {{ $date }}
        </p>
    @endif
</div>