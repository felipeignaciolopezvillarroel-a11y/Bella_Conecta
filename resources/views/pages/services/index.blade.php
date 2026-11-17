@extends('layouts.main')

@section('title', 'Servicios — Bella Conecta')

@section('content')
<section class="py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 bg-pink-100 border border-pink-200 text-pink-600 text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                Todos nuestros servicios
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#1F1B1A] mb-3">
                Servicios de belleza
            </h1>
            <p class="text-gray-600 max-w-xl mx-auto">
                Tratamientos pensados para cada estilo y tipo de cabello.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            @forelse($services as $service)
                <x-service-card
                    :icon="$service->icon"
                    :title="$service->name"
                    :slug="$service->slug"
                    :description="$service->description ?? ''"
                    :price="'$' . number_format($service->price, 0, ',', '.')"
                    :popular="false"
                    :items="[]"
                />
            @empty
                <p class="col-span-full text-center text-gray-500 py-12">No hay servicios disponibles.</p>
            @endforelse
        </div>

    </div>
</section>
@endsection