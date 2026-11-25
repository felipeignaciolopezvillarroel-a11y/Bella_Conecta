@extends('layouts.main')

@section('title', 'Mis reservas')

@section('content')
<section class="py-8 sm:py-12 lg:py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Modal de confirmación para cancelar --}}
        <x-confirm-modal
            name="cancel-booking"
            title="Cancelar reserva"
            confirm-text="Sí, cancelar"
            cancel-text="Volver"
            variant="danger">
            <p>¿Estás segura de que quieres cancelar tu reserva de <strong id="cancel-booking-service" class="text-[#1F1B1A]"></strong>?</p>
            <p class="text-red-600 font-medium">Esta acción no se puede deshacer. El horario quedará libre para otra persona.</p>
        </x-confirm-modal>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 sm:mb-10">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1F1B1A] mb-1.5 sm:mb-2">Mis reservas</h1>
                <p class="text-sm sm:text-base text-gray-600">Consulta, gestiona y revisa el historial de tus citas.</p>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto shrink-0">

                <a href="{{ route('home') }}#servicios"
                    class="cursor-pointer inline-flex items-center justify-center gap-2 bg-pink-600 hover:bg-pink-700 text-white text-sm sm:text-base font-semibold px-5 py-2.5 rounded-full transition shadow-md hover:shadow-pink-500/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva reserva
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="group inline-flex items-center justify-center gap-2 text-sm font-medium text-gray-600 hover:text-pink-600 bg-white hover:bg-pink-50/70 border border-[#EEE3DD] hover:border-pink-200 px-4 py-2.5 rounded-full shadow-xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Volver al perfil</span>
                </a>
            </div>
        </div>

        {{-- ══════════ PRÓXIMAS RESERVAS ══════════ --}}
        <div class="mb-10 sm:mb-12">
            <div class="flex items-center gap-2.5 sm:gap-3 mb-4">
                <h2 class="text-lg sm:text-xl font-bold text-[#1F1B1A]">Próximas reservas</h2>
                <span class="inline-flex items-center justify-center min-w-[1.75rem] h-6 sm:h-7 px-2 rounded-full bg-pink-100 text-pink-700 text-xs font-bold">
                    {{ $bookings->count() }}
                </span>
            </div>

            @if($bookings->isEmpty())
            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-8 sm:p-12 text-center">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">📅</div>
                <h3 class="text-base sm:text-lg font-semibold text-[#1F1B1A] mb-1.5 sm:mb-2">No tienes reservas próximas</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-5 sm:mb-6">Anímate a reservar tu próximo servicio.</p>
                <a href="{{ route('home') }}#servicios"
                    class="cursor-pointer inline-block w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white text-sm sm:text-base font-semibold px-6 py-3 rounded-full transition">
                    Ver servicios
                </a>
            </div>
            @else
            {{-- Contenedor con scroll en escritorio si hay más de 3 reservas --}}
            <div class="grid gap-3.5 sm:gap-4 {{ $bookings->count() > 3 ? 'lg:max-h-[540px] lg:overflow-y-auto lg:pr-2' : '' }}">
                @foreach($bookings as $booking)
                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-4 sm:p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4 sm:gap-5">

                        {{-- Cabecera móvil (Icono + Título + Estado) / En lg se separa en columnas --}}
                        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 flex-1 min-w-0">
                            {{-- Icono servicio --}}
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-xl sm:text-2xl shrink-0">
                                {{ $booking->service->icon }}
                            </div>

                            {{-- Info principal --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <p class="font-bold text-[#1F1B1A] text-base sm:text-lg leading-snug">{{ $booking->service->name }}</p>
                                    @php
                                    $statusMap = [
                                    'pending' => ['label' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-700'],
                                    'confirmed' => ['label' => 'Confirmada', 'class' => 'bg-emerald-100 text-emerald-700'],
                                    'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-gray-100 text-gray-600'],
                                    ];
                                    $st = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-600'];
                                    @endphp
                                    <span class="text-[11px] font-semibold px-2.5 py-0.5 sm:py-1 rounded-full {{ $st['class'] }}">
                                        {{ $st['label'] }}
                                    </span>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-y-1.5 sm:gap-x-5 text-xs sm:text-sm text-gray-600 mt-1.5">
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="capitalize">{{ $booking->date->translatedFormat('l d \d\e F, Y') }}</span>
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                    </span>
                                    @if($booking->employee)
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $booking->employee->name }}
                                    </span>
                                    @endif
                                </div>

                                @if($booking->notes)
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2 break-words">📝 {{ $booking->notes }}</p>
                                @endif

                                <p class="text-xs text-pink-600 font-mono mt-2">Código: {{ $booking->code }}</p>
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#EEE3DD] lg:pt-0 lg:border-t-0 shrink-0">
                            @php
                            $bookingDateTime = $booking->start_time->copy()->setDate(
                            $booking->date->year,
                            $booking->date->month,
                            $booking->date->day
                            );
                            $canCancel = now()->lt($bookingDateTime->copy()->subHours(24));
                            @endphp

                            @if($canCancel)
                            {{-- Formulario oculto --}}
                            <form
                                id="cancel-booking-form-{{ $booking->id }}"
                                action="{{ route('bookings.destroy', $booking) }}"
                                method="POST"
                                class="hidden"
                                x-data
                                x-on:confirmed-cancel-booking.window="
                                    if ($event.detail?.id === {{ $booking->id }}) $el.submit()
                                ">
                                @csrf
                                @method('DELETE')
                            </form>

                            {{-- Botón --}}
                            <button
                                type="button"
                                x-data
                                x-on:click="
                                    document.getElementById('cancel-booking-service').textContent = @js($booking->service->name);
                                    $dispatch('open-confirm-cancel-booking', { id: {{ $booking->id }} });
                                "
                                class="cursor-pointer w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs sm:text-sm text-red-600 hover:text-white hover:bg-red-600 border border-red-200 hover:border-red-600 font-semibold px-4 py-2 rounded-full transition"
                                title="Cancelar reserva">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </button>
                            @else
                            <span class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs sm:text-sm text-gray-400 bg-gray-50 border border-gray-200 font-semibold px-4 py-2 rounded-full cursor-not-allowed" title="Solo puedes cancelar con más de 24 horas de anticipación">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                No cancelable
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ══════════ HISTORIAL ══════════ --}}
        <div>
            <div class="flex items-center gap-2.5 sm:gap-3 mb-4">
                <h2 class="text-lg sm:text-xl font-bold text-[#1F1B1A]">Historial</h2>
                <span class="inline-flex items-center justify-center min-w-[1.75rem] h-6 sm:h-7 px-2 rounded-full bg-gray-200 text-gray-700 text-xs font-bold">
                    {{ $history->total() }}
                </span>
            </div>

            @if($history->isEmpty())
            <div class="bg-white border border-[#EEE3DD] rounded-2xl p-8 sm:p-10 text-center">
                <div class="text-3xl sm:text-4xl mb-2.5 sm:mb-3">🗂️</div>
                <p class="text-gray-600 text-xs sm:text-sm">Todavía no tienes reservas en tu historial.</p>
            </div>
            @else
            <div class="bg-white border border-[#EEE3DD] rounded-2xl overflow-hidden shadow-sm">
                {{-- Scroll en escritorio si hay más de 5 reservas en el historial --}}
                <ul class="divide-y divide-[#EEE3DD] {{ $history->count() > 5 ? 'lg:max-h-[420px] lg:overflow-y-auto' : '' }}">
                    @foreach($history as $booking)
                    <li class="p-4 sm:p-5 hover:bg-pink-50/40 transition">
                        <div class="flex items-start sm:items-center gap-3.5 sm:gap-4">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-gray-100 flex items-center justify-center text-lg sm:text-xl shrink-0">
                                {{ $booking->service->icon }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <p class="font-semibold text-sm sm:text-base text-[#1F1B1A] truncate">{{ $booking->service->name }}</p>
                                    @php
                                    $statusMap = [
                                    'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-gray-100 text-gray-600'],
                                    'completed' => ['label' => 'Completada', 'class' => 'bg-emerald-100 text-emerald-700'],
                                    'pending' => ['label' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-700'],
                                    'confirmed' => ['label' => 'Confirmada', 'class' => 'bg-emerald-100 text-emerald-700'],
                                    ];
                                    $st = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-600'];
                                    @endphp
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $st['class'] }}">
                                        {{ $st['label'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    {{ $booking->date->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                    @if($booking->employee) · con {{ $booking->employee->name }} @endif
                                    <span class="block sm:inline mt-0.5 sm:mt-0">
                                        <span class="hidden sm:inline">· </span><span class="font-mono text-pink-600">{{ $booking->code }}</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Paginación --}}
            @if($history->hasPages())
            <div class="mt-6">
                {{ $history->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</section>
@endsection