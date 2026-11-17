@extends('layouts.main')

@section('title', 'Estilistas — Bella Conecta')

@section('content')
<section class="py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                Nuestro equipo
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#1F1B1A] mb-3">
                Conoce a nuestras estilistas
            </h1>
            <p class="text-gray-600 max-w-xl mx-auto">
                Profesionales con años de experiencia y pasión por la belleza.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($stylists as $stylist)
                <x-stylist-card
                    :name="$stylist->name"
                    :role="$stylist->role ?? 'Estilista'"
                    :image="$stylist->image ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80'"
                />
            @empty
                <p class="col-span-full text-center text-gray-500 py-12">No hay estilistas disponibles.</p>
            @endforelse
        </div>

    </div>
</section>
@endsection