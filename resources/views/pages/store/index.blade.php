@extends('layouts.main')

@section('title', 'Productos — Bella Conecta')

@section('content')
<section class="py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                Tienda Bella Conecta
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#1F1B1A] mb-3">
                Todos nuestros productos
            </h1>
            <p class="text-gray-600 max-w-xl mx-auto">
                Lleva a casa el cuidado profesional que usamos en el salón.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $p)
                <x-product-card
                    :icon="$p['icon']"
                    :name="$p['name']"
                    :price="$p['price']"
                    :rating="$p['rating']"
                    :reviews="$p['reviews']"
                    :badge="$p['badge']"
                />
            @endforeach
        </div>

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
                <a href="#" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition shadow-md shrink-0">
                    Reservar productos
                </a>
            </div>
        </div>

    </div>
</section>
@endsection