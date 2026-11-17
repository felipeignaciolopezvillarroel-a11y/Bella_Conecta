@extends('layouts.main')

@section('title', 'Portal de trabajadores — Bella Conecta')

@section('content')
<section class="py-12 sm:py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-[#1F1B1A]">
                Portal de trabajadores
            </h1>
            <p class="text-gray-600 mt-2">¡Hola, {{ auth()->user()->name }}! 👋</p>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6">
                <p class="text-sm text-gray-500 mb-1">Reservas hoy</p>
                <p class="text-3xl font-bold text-[#1F1B1A]">
                    {{ \App\Models\Booking::whereDate('date', today())->whereIn('status', ['pending', 'confirmed'])->count() }}
                </p>
            </div>

            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6">
                <p class="text-sm text-gray-500 mb-1">Esta semana</p>
                <p class="text-3xl font-bold text-[#1F1B1A]">
                    {{ \App\Models\Booking::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->whereIn('status', ['pending', 'confirmed'])->count() }}
                </p>
            </div>

            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6">
                <p class="text-sm text-gray-500 mb-1">Servicios activos</p>
                <p class="text-3xl font-bold text-[#1F1B1A]">
                    {{ \App\Models\Service::where('active', true)->count() }}
                </p>
            </div>

            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6">
                <p class="text-sm text-gray-500 mb-1">Estilistas</p>
                <p class="text-3xl font-bold text-[#1F1B1A]">
                    {{ \App\Models\Stylist::where('active', true)->count() }}
                </p>
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="#" class="bg-white border border-[#EEE3DD] rounded-2xl p-6 hover:shadow-lg hover:border-pink-200 transition group">
                <div class="text-3xl mb-3">📅</div>
                <h3 class="font-semibold text-[#1F1B1A] mb-1 group-hover:text-pink-600 transition">Ver reservas</h3>
                <p class="text-sm text-gray-500">Todas las citas del salón</p>
            </a>

            <a href="#" class="bg-white border border-[#EEE3DD] rounded-2xl p-6 hover:shadow-lg hover:border-pink-200 transition group">
                <div class="text-3xl mb-3">💇</div>
                <h3 class="font-semibold text-[#1F1B1A] mb-1 group-hover:text-pink-600 transition">Gestionar servicios</h3>
                <p class="text-sm text-gray-500">Crear y editar servicios</p>
            </a>

            <a href="#" class="bg-white border border-[#EEE3DD] rounded-2xl p-6 hover:shadow-lg hover:border-pink-200 transition group">
                <div class="text-3xl mb-3">👥</div>
                <h3 class="font-semibold text-[#1F1B1A] mb-1 group-hover:text-pink-600 transition">Gestionar estilistas</h3>
                <p class="text-sm text-gray-500">Equipo del salón</p>
            </a>
        </div>

    </div>
</section>
@endsection