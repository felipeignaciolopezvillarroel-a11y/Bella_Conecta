@extends('layouts.main')

@section('title', 'Reservar ' . $service->name)

@section('content')
<section class="py-12 sm:py-6 bg-[#FDF8F5] min-h-screen">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-12">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-[#1F1B1A] mt-3">
                    Reservar: {{ $service->name }} {{ $service->icon }}
                </h1>
                <p class="text-gray-600 mt-2">
                    Duración: {{ $service->duration_minutes }} min · Precio: ${{ number_format($service->price, 0, ',', '.') }}
                </p>
            </div>
            
            <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-pink-600 bg-white hover:bg-pink-50/70 border border-[#EEE3DD] hover:border-pink-200 px-3.5 py-2 rounded-full shadow-xs transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Volver</span>
            </a>
        </div>

        @guest
        <div class="bg-white border border-pink-200 rounded-2xl p-8 text-center">
            <div class="text-4xl mb-3">🔒</div>
            <h2 class="text-xl font-bold text-[#1F1B1A] mb-2">Inicia sesión para reservar</h2>
            <p class="text-gray-600 mb-6 text-sm">
                Necesitas una cuenta para confirmar tu reserva. Es rápido y gratis.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-full transition">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}"
                    class="border-2 border-pink-600 text-pink-600 hover:bg-pink-600 hover:text-white font-semibold px-6 py-3 rounded-full transition">
                    Crear cuenta
                </a>
            </div>
        </div>
        @endguest

        @auth
        @php
        $employeesJson = $employees->map(fn($e) => [
        'id' => (string) $e->id,
        'name' => $e->name,
        'role' => $e->role ?: 'Estilista',
        ])->values();
        @endphp

        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8 items-start" x-data="reservationForm($el)"
            data-service-id="{{ $service->id }}"
            data-today="{{ now()->toDateString() }}"
            data-tomorrow="{{ now()->addDay()->toDateString() }}"
            data-max-date="{{ now()->addDays(60)->toDateString() }}"
            data-view-year="{{ now()->year }}"
            data-view-month="{{ now()->month }}"
            data-employees='@json($employeesJson)'
            data-selected-employee="{{ $selectedEmployeeId ?? '' }}"
            data-route-slots="{{ route('bookings.slots') }}"
            data-route-month="{{ route('bookings.month') }}">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-3xl mb-4">
                        {{ $service->icon }}
                    </div>
                    <h3 class="text-xl font-bold text-[#1F1B1A] mb-1">{{ $service->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ $service->description }}</p>
                    <div class="space-y-3 pt-4 border-t border-[#EEE3DD]">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Duración</span>
                            <span class="font-semibold text-[#1F1B1A]">{{ $service->duration_minutes }} min</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Precio</span>
                            <span class="font-semibold text-pink-600 text-lg">${{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm">
                    <h4 class="font-semibold text-[#1F1B1A] mb-4 text-sm uppercase tracking-wider">¿Cómo funciona?</h4>
                    <ol class="space-y-3 text-sm text-gray-600">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                            <span>Elige fecha y hora disponible</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0">2</span>
                            <span>Confirma tu reserva</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0">3</span>
                            <span>Recibe tu código de reserva</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold shrink-0">4</span>
                            <span>Asiste a tu cita y paga en tienda</span>
                        </li>
                    </ol>
                </div>

                <div class="bg-pink-50 border border-pink-200 rounded-2xl p-6">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">💡</span>
                        <div>
                            <p class="font-semibold text-[#1F1B1A] text-sm">¿Necesitas cambiar tu hora?</p>
                            <p class="text-xs text-gray-600 mt-1">
                                Puedes cancelar hasta 24h antes desde "Mis reservas".
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-lg p-6 sm:p-8">

                    <h2 class="text-xl font-bold text-[#1F1B1A] mb-6">Elige tu horario</h2>

                    <form method="POST" action="{{ route('bookings.store', $service->slug) }}">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-5 mb-5">

                                                        {{-- ══════════ ESTILISTA ══════════ --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                                    Estilista <span class="text-pink-600">*</span>
                                </label>

                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <select name="employee_id"
                                        x-model="employeeId"
                                        @change="loadSlots()"
                                        class="sr-only"
                                        required
                                        tabindex="-1">
                                        <option value="">Selecciona un estilista</option>
                                        @foreach($employees as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }} — {{ $s->role }}</option>
                                        @endforeach
                                    </select>

                                    {{-- Trigger visual --}}
                                    <button type="button" @click="open = !open"
                                        class="cursor-pointer w-full flex items-center gap-3 pl-3.5 pr-3 py-2.5 rounded-xl border border-[#EEE3DD] bg-white hover:border-pink-300 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-left">
                                        
                                        <template x-if="employeeId === ''">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-semibold text-gray-400">Selecciona un estilista</p>
                                                <p class="text-xs text-gray-400">Elige quién te atenderá</p>
                                            </div>
                                        </template>

                                        <template x-if="employeeId !== ''">
                                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-xs font-bold text-pink-600 shrink-0"
                                                    x-text="selectedEmployeeInitial()"></div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-[#1F1B1A] truncate" x-text="selectedEmployeeName()"></p>
                                                    <p class="text-xs text-gray-500 truncate" x-text="selectedEmployeeRole()"></p>
                                                </div>
                                            </div>
                                        </template>

                                        <span class="text-gray-400 transition shrink-0" :class="open ? 'rotate-180' : ''">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>
                                    </button>

                                    {{-- Panel de opciones --}}
                                    <div x-show="open" x-transition
                                        class="absolute z-30 mt-2 w-full bg-white border border-[#EEE3DD] rounded-2xl shadow-xl p-2 max-h-72 overflow-y-auto">
                                        
                                        @foreach($employees as $s)
                                        <button type="button"
                                            @click="selectEmployee('{{ $s->id }}'); open = false"
                                            class="w-full flex items-center gap-3 p-2.5 rounded-xl transition text-left"
                                            :class="employeeId === '{{ $s->id }}' ? 'bg-pink-50 ring-1 ring-pink-200' : 'hover:bg-pink-50/60'">
                                            @if($s->photo_url)
                                            <img src="{{ $s->photo_url }}" alt="{{ $s->name }}"
                                                class="w-9 h-9 rounded-full object-cover shrink-0 ring-2 ring-white">
                                            @else
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-100 to-fuchsia-100 flex items-center justify-center text-xs font-bold text-pink-600 shrink-0">
                                                {{ strtoupper(substr($s->name, 0, 1)) }}
                                            </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-[#1F1B1A] truncate">{{ $s->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $s->role ?: 'Estilista' }}</p>
                                            </div>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- ══════════ FECHA ══════════ --}}
                            <div @click.outside="calOpen = false">
                                <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Fecha</label>

                                <input type="hidden" name="date" x-model="date" required>

                                <div class="relative">
                                    <button type="button" @click="calOpen = !calOpen"
                                        class="cursor-pointer w-full flex items-center gap-3 pl-3.5 pr-3 py-2.5 rounded-xl border border-[#EEE3DD] bg-white hover:border-pink-300 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-left">
                                        <span class="text-pink-500 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </span>
                                        <span class="flex-1 text-sm font-semibold text-[#1F1B1A]"
                                            x-text="formattedDate"></span>
                                        <span class="text-gray-400 transition shrink-0" :class="calOpen ? 'rotate-180' : ''">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>
                                    </button>

                                    {{-- Panel calendario --}}
                                    <div x-show="calOpen" x-transition
                                        class="absolute z-30 mt-2 w-full sm:w-80 right-0 bg-white border border-[#EEE3DD] rounded-2xl shadow-xl p-4">

                                        <div class="flex items-center justify-between mb-3">
                                            <button type="button" @click="prevMonth()"
                                                class="w-8 h-8 rounded-full hover:bg-pink-50 text-gray-500 hover:text-pink-600 flex items-center justify-center transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <p class="text-sm font-bold text-[#1F1B1A]" x-text="monthLabel"></p>
                                            <button type="button" @click="nextMonth()"
                                                class="w-8 h-8 rounded-full hover:bg-pink-50 text-gray-500 hover:text-pink-600 flex items-center justify-center transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1 mb-1">
                                            <template x-for="d in ['L','M','X','J','V','S','D']">
                                                <div class="text-center text-[10px] font-bold text-gray-400 uppercase py-1" x-text="d"></div>
                                            </template>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="(day, idx) in calendarDays" :key="idx">
                                                <button type="button"
                                                    @click="selectDay(day)"
                                                    :disabled="!day.enabled || !day.date"
                                                    class="aspect-square rounded-lg text-xs font-medium transition flex items-center justify-center relative"
                                                    :class="{
                                                        'invisible': !day.date,
                                                        'text-gray-300 cursor-not-allowed line-through': day.date && (!day.enabled || day.blocked),
                                                        'text-gray-700 hover:bg-pink-50 hover:text-pink-600': day.date && day.enabled && !day.blocked && day.date !== date,
                                                        'bg-pink-600 text-white font-bold shadow-md ring-2 ring-pink-200': day.date === date,
                                                        'bg-pink-50 text-pink-600 font-semibold': day.date && day.enabled && !day.blocked && day.date !== date && day.isToday
                                                    }"
                                                    x-text="day.label">
                                                </button>
                                            </template>
                                        </div>

                                        <div class="flex items-center justify-center gap-4 mt-3 pt-3 border-t border-[#EEE3DD] text-[10px] text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <span class="w-2.5 h-2.5 rounded-full bg-pink-600"></span> Seleccionado
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="w-2.5 h-2.5 rounded-full bg-gray-200"></span> Sin cupo
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Slots de hora --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Hora disponible</label>

                            {{-- Estado: cargando --}}
                            <div x-show="loading" class="flex flex-col items-center justify-center py-8 gap-3">
                                <div class="relative w-10 h-10">
                                    {{-- Anillo base --}}
                                    <div class="absolute inset-0 rounded-full border-4 border-pink-100"></div>
                                    {{-- Anillo giratorio --}}
                                    <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-pink-600 animate-spin"></div>
                                </div>
                                <p class="text-sm text-gray-500">Cargando horarios disponibles…</p>
                            </div>
                            
                            {{-- Estado: sin slots --}}
                            <div x-show="!loading && slots.length === 0" class="text-sm text-gray-500 py-4">
                                No hay horarios disponibles para esa fecha.
                            </div>

                            <div x-show="!loading && slots.length > 0"
                                class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                                <template x-for="slot in slots" :key="slot.time">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="start_time" :value="slot.time"
                                            x-model="selectedTime"
                                            :disabled="!slot.available" class="peer sr-only">
                                        <div class="text-center text-sm py-2.5 rounded-xl border-2 transition"
                                            :class="{
                                                'border-[#EEE3DD] bg-white text-gray-700 hover:border-pink-300': slot.available && selectedTime !== slot.time,
                                                'border-pink-500 bg-pink-50 text-pink-700 font-semibold': selectedTime === slot.time,
                                                'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed line-through': !slot.available
                                            }"
                                            x-text="slot.label"></div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                                Notas <span class="text-gray-400 font-normal">(opcional)</span>
                            </label>
                            <textarea name="notes" rows="3" maxlength="500"
                                placeholder="Ej: Prefiero un tono rubio ceniza, tengo el cabello largo…"
                                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none resize-none"></textarea>
                        </div>

                        <button type="submit"
                            class="cursor-pointer w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3.5 rounded-full transition shadow-md hover:shadow-pink-500/40">
                            Confirmar reserva
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Lógica del formulario --}}
        @push('scripts')
        <script>
            window.reservationConfig = {
                serviceId: {{ $service->id }},
                today:     '{{ now()->toDateString() }}',
                tomorrow:  '{{ now()->addDay()->toDateString() }}',
                maxDate:   '{{ now()->addDays(60)->toDateString() }}',
                viewYear:  {{ now()->year }},
                viewMonth: {{ now()->month }},
                employees: @json($employeesJson),
                routes: {
                    slots: '{{ route('bookings.slots') }}',
                    month: '{{ route('bookings.month') }}',
                },
            };
        </script>
        @endpush
        @endauth
    </div>
</section>
@endsection