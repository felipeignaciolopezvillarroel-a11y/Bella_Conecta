@extends('layouts.admin')

@section('title', 'Reservas')
@section('page_title', 'Gestión de Reservas')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Listado de Reservas</h2>
            <p class="text-sm text-gray-500 mt-1">Administra todas las citas agendadas por los clientes.</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">

            {{-- Búsqueda --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cliente, código o servicio..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Estado --}}
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Estado</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm bg-white">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            {{-- Fecha --}}
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Fecha</label>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
            </div>

            {{-- Botones --}}
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm text-sm">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status', 'date']))
                <a href="{{ route('admin.bookings.index') }}" class="w-full sm:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                    Limpiar
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#FDF8F5] text-gray-500 border-b border-[#EEE3DD]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Código</th>
                        <th class="px-6 py-4 font-semibold">Cliente</th>
                        <th class="px-6 py-4 font-semibold">Servicio</th>
                        <th class="px-6 py-4 font-semibold">Fecha y Hora</th>
                        <th class="px-6 py-4 font-semibold">Estilista</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-pink-600">
                            {{ $booking->code }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#1F1B1A]">{{ $booking->user->name ?? 'Cliente eliminado' }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->user->email ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            {{ $booking->service->icon ?? '' }} {{ $booking->service->name ?? 'Servicio eliminado' }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium">{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            {{ $booking->employee->name ?? 'Cualquiera' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'confirmed' => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                            'pending' => 'Pendiente',
                            'confirmed' => 'Confirmada',
                            'completed' => 'Completada',
                            'cancelled' => 'Cancelada',
                            ];
                            @endphp
                            <span class="{{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-bold px-2.5 py-1 rounded-full">
                                {{ $statusLabels[$booking->status] ?? $booking->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Menú de cambio de estado --}}
                                <div x-data="{ 
            open: false, 
            top: 0, 
            left: 0,
            toggleMenu(event) {
                this.open = !this.open;
                if (this.open) {
                    const rect = event.currentTarget.getBoundingClientRect();
                    this.top = rect.bottom + 8;
                    this.left = rect.right - 125;
                }
            }
         }"
                                    x-init="
            window.addEventListener('scroll', () => { open = false; }, { passive: true });
         "
                                    class="relative">

                                    <button @click="toggleMenu($event)" class="cursor-pointer p-2 text-gray-500 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition" title="Cambiar estado">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

                                    {{-- Menú flotante --}}
                                    <div x-show="open"
                                        x-cloak
                                        @click.away="open = false"
                                        x-transition.opacity.duration.200ms
                                        class="fixed z-[9999] w-40 bg-white border border-[#EEE3DD] rounded-xl shadow-2xl overflow-hidden"
                                        x-bind:style="`top: ${top}px; left: ${left}px;`">

                                        @foreach(['pending' => 'Pendiente', 'confirmed' => 'Confirmada', 'completed' => 'Completada', 'cancelled' => 'Cancelada'] as $val => $label)
                                        <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $val }}">
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition {{ $booking->status === $val ? 'font-bold bg-pink-50 text-pink-600' : '' }}">
                                                {{ $label }}
                                            </button>
                                        </form>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Eliminar --}}
                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cursor-pointer p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            @if(request()->anyFilled(['search', 'status', 'date']))
                            {{-- CASO 1: Filtros sin resultados --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">No se encontraron reservas</p>
                                    <p class="text-sm text-gray-500 mt-1">Intenta con otros filtros.</p>
                                </div>
                                <a href="{{ route('admin.bookings.index') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-pink-600 hover:text-pink-800 transition">
                                    Limpiar filtros
                                </a>
                            </div>
                            @else
                            {{-- CASO 2: No hay reservas --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center text-pink-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">Aún no hay reservas</p>
                                    <p class="text-sm text-gray-500 mt-1">Las reservas de los clientes aparecerán aquí.</p>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-[#EEE3DD] bg-[#FDF8F5]">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

</div>
@endsection