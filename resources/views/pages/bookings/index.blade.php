@extends('layouts.main')

@section('title', 'Mis reservas')

@section('content')
<section class="py-12 sm:py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-[#1F1B1A] mb-2">Mis reservas</h1>
                <p class="text-gray-600">Consulta, gestiona y revisa el historial de tus citas.</p>
            </div>
            <a href="{{ route('home') }}#servicios"
               class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-md hover:shadow-pink-500/40 self-start sm:self-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nueva reserva
            </a>
        </div>

        {{-- ══════════ PRÓXIMAS RESERVAS ══════════ --}}
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-xl font-bold text-[#1F1B1A]">Próximas reservas</h2>
                <span class="inline-flex items-center justify-center min-w-[1.75rem] h-7 px-2 rounded-full bg-pink-100 text-pink-700 text-xs font-bold">
                    {{ $bookings->count() }}
                </span>
            </div>

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
                <div class="grid gap-4">
                    @foreach($bookings as $booking)
                        <div class="bg-white border border-[#EEE3DD] rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-md transition">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                                {{-- Icono servicio --}}
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-2xl shrink-0">
                                    {{ $booking->service->icon }}
                                </div>

                                {{-- Info principal --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <p class="font-bold text-[#1F1B1A] text-lg">{{ $booking->service->name }}</p>
                                        @php
                                            $statusMap = [
                                                'pending'   => ['label' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-700'],
                                                'confirmed' => ['label' => 'Confirmada', 'class' => 'bg-emerald-100 text-emerald-700'],
                                                'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-gray-100 text-gray-600'],
                                            ];
                                            $st = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-600'];
                                        @endphp
                                        <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $st['class'] }}">
                                            {{ $st['label'] }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 gap-y-1 text-sm text-gray-600 mt-1">
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $booking->date->translatedFormat('l d \d\e F, Y') }}
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </span>
                                        @if($booking->employee)
                                            <span class="flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $booking->employee->name }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($booking->notes)
                                        <p class="text-xs text-gray-500 mt-2 line-clamp-2">📝 {{ $booking->notes }}</p>
                                    @endif

                                    <p class="text-xs text-pink-600 font-mono mt-2">Código: {{ $booking->code }}</p>
                                </div>

                                {{-- Acciones --}}
                                <div class="flex items-center gap-2 shrink-0">
                                    <form method="POST" action="{{ route('bookings.destroy', $booking) }}"
                                          onsubmit="return confirm('¿Cancelar esta reserva?')">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center gap-2 text-sm text-red-600 hover:text-white hover:bg-red-600 border border-red-200 hover:border-red-600 font-semibold px-4 py-2 rounded-full transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Cancelar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ══════════ HISTORIAL ══════════ --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-xl font-bold text-[#1F1B1A]">Historial</h2>
                <span class="inline-flex items-center justify-center min-w-[1.75rem] h-7 px-2 rounded-full bg-gray-200 text-gray-700 text-xs font-bold">
                    {{ $history->total() }}
                </span>
            </div>

            @if($history->isEmpty())
                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-10 text-center">
                    <div class="text-4xl mb-3">🗂️</div>
                    <p class="text-gray-600 text-sm">Todavía no tienes reservas en tu historial.</p>
                </div>
            @else
                <div class="bg-white border border-[#EEE3DD] rounded-2xl overflow-hidden shadow-sm">
                    <ul class="divide-y divide-[#EEE3DD]">
                        @foreach($history as $booking)
                            <li class="p-4 sm:p-5 hover:bg-pink-50/40 transition">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center text-xl shrink-0">
                                        {{ $booking->service->icon }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                            <p class="font-semibold text-[#1F1B1A] truncate">{{ $booking->service->name }}</p>
                                            @php
                                                $statusMap = [
                                                    'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-gray-100 text-gray-600'],
                                                    'completed' => ['label' => 'Completada', 'class' => 'bg-emerald-100 text-emerald-700'],
                                                    'pending'   => ['label' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-700'],
                                                    'confirmed' => ['label' => 'Confirmada', 'class' => 'bg-emerald-100 text-emerald-700'],
                                                ];
                                                $st = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-600'];
                                            @endphp
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $st['class'] }}">
                                                {{ $st['label'] }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            {{ $booking->date->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                            @if($booking->employee) · con {{ $booking->employee->name }} @endif
                                            · <span class="font-mono text-pink-600">{{ $booking->code }}</span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Paginación --}}
                <div class="mt-6">
                    {{ $history->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection