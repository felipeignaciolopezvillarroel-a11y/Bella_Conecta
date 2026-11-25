@extends('layouts.main')

@section('title', 'Productos — Bella Conecta')

@section('content')
<section class="relative py-8 sm:py-12 lg:py-16 bg-[#FDF8F5] min-h-screen overflow-hidden"
    x-data="cartApp()"
    x-init="
            isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            loginUrl = '{{ route('login') }}';
            hasClearCartFlag = {{ session()->has('clear_cart') ? 'true' : 'false' }};
         "
    x-cloak>
    
    {{-- Decoración de fondo --}}
    <div class="pointer-events-none absolute -top-24 -left-24 w-64 h-64 sm:w-96 sm:h-96 bg-pink-200/40 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -top-16 -right-32 w-60 h-60 sm:w-80 sm:h-80 bg-rose-100/60 rounded-full blur-3xl"></div>

    <div class="relative w-full mx-auto px-4 sm:px-8 lg:px-12">

        <div class="flex flex-col-reverse sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-6 mb-8 sm:mb-12 lg:mb-16">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold text-[#1F1B1A] leading-[1.1] sm:leading-[1.05] mb-3 sm:mb-5">
                    Productos de <span class="text-pink-600">belleza</span> pensados para ti
                </h1>

                <p class="text-sm sm:text-base lg:text-lg text-gray-600 leading-relaxed">
                    Lleva a casa el cuidado profesional que usamos en el salón.
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

        {{-- Reutilizamos el componente de tarjetas --}}
        <x-order-cards :products="$products" grid-class="grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6" />

        {{-- Banner reserva --}}
        <div class="mt-12 relative overflow-hidden bg-white border border-[#EEE3DD] rounded-2xl p-6 sm:p-8">
            <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-pink-100/60 blur-3xl"></div>
            <div class="relative flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 border border-pink-200 flex items-center justify-center shrink-0">
                        <span class="text-2xl">🏬</span>
                    </div>
                    <div class="text-[#1F1B1A]">
                        <p class="font-semibold text-lg mb-1">Reserva online, paga y retira en tienda</p>
                        <p class="text-sm text-gray-600">Elige tus productos, genera tu ticket de retiro y paga al buscarlos en el salón.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════ CARRITO FLOTANTE Y MODAL (Igual que en el Home) ══════════ --}}
    <div x-show="cart.length > 0 && isAuthenticated" class="fixed bottom-6 right-6 z-40 print:hidden" x-transition>
        <button @click="openCheckout = true" class="cursor-pointer bg-pink-600 hover:bg-pink-700 text-white rounded-full p-4 shadow-xl flex items-center gap-3 transition-all hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="font-bold text-lg" x-text="cart.length"></span>
            <span class="font-semibold" x-text="'$' + formatPrice(cartTotal)"></span>
        </button>
    </div>

    {{-- Modal de Checkout --}}
    <div x-show="openCheckout && isAuthenticated" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" style="display: none;" x-transition>
        <div @click.outside="openCheckout = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">

            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-pink-50">
                <h3 class="text-xl font-bold text-[#1F1B1A]">Tu Carrito</h3>
                <button @click="openCheckout = false" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <template x-for="item in cart" :key="item.id">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-100">
                        <img :src="item.image" class="w-16 h-16 object-contain bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-[#1F1B1A]" x-text="item.name"></p>
                            <p class="text-sm text-pink-600 font-bold" x-text="'$' + formatPrice(item.price)"></p>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                            <button @click="updateQty(item.id, -1)" class="w-6 h-6 flex items-center justify-center hover:bg-white rounded transition">-</button>
                            <span class="text-sm font-semibold w-4 text-center" x-text="item.quantity"></span>
                            <button @click="updateQty(item.id, 1)" class="w-6 h-6 flex items-center justify-center hover:bg-white rounded transition">+</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 font-semibold">Total a pagar:</span>
                    <span class="text-2xl font-bold text-[#1F1B1A]" x-text="'$' + formatPrice(cartTotal)"></span>
                </div>

                <form action="{{ route('orders.store') }}" method="POST" @submit="isSubmitting = true">
                    @csrf
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">

                    @auth
                    <div class="bg-white border border-gray-200 rounded-xl p-3 mb-4">
                        <p class="text-xs text-gray-500 mb-1">Boleta a nombre de:</p>
                        <p class="text-sm font-semibold text-[#1F1B1A]">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    @endauth

                    <button type="submit"
                        :disabled="isSubmitting || cart.length === 0"
                        class="cursor-pointer w-full bg-pink-600 hover:bg-pink-700 disabled:bg-gray-400 text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2">
                        <span x-show="!isSubmitting">Generar Boleta de Retiro</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generando...
                        </span>
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-3">Al generar la boleta, podrás ir a pagar y retirar a la tienda.</p>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection