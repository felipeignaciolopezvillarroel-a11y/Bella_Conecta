@extends('layouts.main')

@section('title', 'Reservar ' . $service->name)

@section('content')
<section class="py-12 sm:py-16 bg-[#FDF8F5] min-h-screen">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-8 lg:px-12">

        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-pink-600">← Volver</a>
            <h1 class="text-3xl sm:text-4xl font-bold text-[#1F1B1A] mt-3">
                Reservar: {{ $service->name }} {{ $service->icon }}
            </h1>
            <p class="text-gray-600 mt-2">
                Duración: {{ $service->duration_minutes }} min · Precio: ${{ number_format($service->price, 0, ',', '.') }}
            </p>
        </div>

        {{-- Invitación a login si no está logueado --}}
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

        {{-- Formulario (solo logueados) --}}
        @auth
            <div class="grid lg:grid-cols-3 gap-6 lg:gap-8 items-start"
                 x-data="reservationForm({{ $service->id }})">

                {{-- COLUMNA IZQUIERDA: Resumen del servicio --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Card del servicio --}}
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

                    {{-- Pasos --}}
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

                    {{-- Info extra --}}
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

                {{-- COLUMNA DERECHA: Formulario --}}
                <div class="lg:col-span-2">
                    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-lg p-6 sm:p-8">

                        <h2 class="text-xl font-bold text-[#1F1B1A] mb-6">Elige tu horario</h2>

                        <form method="POST" action="{{ route('bookings.store', $service->slug) }}">
                            @csrf

                            <div class="grid md:grid-cols-2 gap-5 mb-5">

                                {{-- Estilista --}}
                                <div>
                                    <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                                        Estilista <span class="text-gray-400 font-normal">(opcional)</span>
                                    </label>
                                    <select x-model="stylistId" @change="loadSlots()"
                                            name="stylist_id"
                                            class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none">
                                        <option value="">Cualquiera disponible</option>
                                        @foreach($stylists as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }} — {{ $s->role }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Fecha --}}
                                <div>
                                    <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Fecha</label>
                                    <input type="date" name="date" x-model="date" @change="loadSlots()"
                                           min="{{ now()->toDateString() }}"
                                           max="{{ now()->addDays(60)->toDateString() }}"
                                           required
                                           class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none">
                                    @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Slots de hora --}}
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Hora disponible</label>

                                <div x-show="loading" class="text-sm text-gray-500 py-4">Cargando horarios…</div>

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
                                @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3.5 rounded-full transition shadow-md hover:shadow-pink-500/40">
                                Confirmar reserva
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- Alpine: lógica del formulario --}}
            @push('scripts')
            <script>
                function reservationForm(serviceId) {
                    return {
                        stylistId: '',
                        date: '{{ now()->addDay()->toDateString() }}',
                        slots: [],
                        selectedTime: '',
                        loading: false,
                        init() { this.loadSlots(); },
                        async loadSlots() {
                            this.loading = true;
                            this.selectedTime = '';
                            try {
                                const params = new URLSearchParams({
                                    service_id: serviceId,
                                    date: this.date,
                                });
                                if (this.stylistId) params.append('stylist_id', this.stylistId);
                                const res = await fetch(`{{ route('bookings.slots') }}?${params}`, {
                                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                const data = await res.json();
                                this.slots = data.slots || [];
                            } catch (e) {
                                this.slots = [];
                            } finally {
                                this.loading = false;
                            }
                        }
                    };
                }
            </script>
            @endpush
        @endauth
    </div>
</section>
@endsection