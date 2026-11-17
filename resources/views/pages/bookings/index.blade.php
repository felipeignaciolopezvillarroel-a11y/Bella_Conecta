@extends('layouts.main')

@section('title', 'Mis reservas')

@section('content')
<section class="py-16 bg-[#FDF8F5] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-8">

        <h1 class="text-3xl sm:text-4xl font-bold text-[#1F1B1A] mb-2">Mis reservas</h1>
        <p class="text-gray-600 mb-8">Aquí puedes ver y cancelar tus próximas citas.</p>

        @if($bookings->isEmpty())
            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-12 text-center">
                <div class="text-5xl mb-4">📅</div>
                <h3 class="text-lg font-semibold text-[#1F1B1A] mb-2">No tienes reservas próximas</h3>
                <p class="text-gray-600 mb-6">Anímate a reservar tu próximo servicio.</p>
                <a href="{{ route('home') }}#servicios"
                   class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition">
                    Ver servicios
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-pink-50 flex items-center justify-center text-2xl shrink-0">
                            {{ $booking->service->icon }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-[#1F1B1A]">{{ $booking->service->name }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $booking->date->format('l d/m/Y') }} · {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                            </p>
                            @if($booking->stylist)
                                <p class="text-xs text-gray-500 mt-1">Con {{ $booking->stylist->name }}</p>
                            @endif
                            <p class="text-xs text-pink-600 font-mono mt-1">Código: {{ $booking->code }}</p>
                        </div>
                        <form method="POST" action="{{ route('bookings.destroy', $booking) }}"
                              onsubmit="return confirm('¿Cancelar esta reserva?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-red-600 hover:text-red-800 font-semibold">
                                Cancelar
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection