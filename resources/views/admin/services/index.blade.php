@extends('layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Gestión de Servicios')

@section('content')
<div class="space-y-6">

    {{-- Modal de confirmación (uno solo para toda la tabla) --}}
    <x-confirm-modal
        name="delete-service"
        title="Eliminar servicio"
        confirm-text="Eliminar servicio"
        cancel-text="Cancelar"
        variant="danger">
        <p>
            Vas a eliminar el servicio
            <strong id="delete-service-name" class="text-[#1F1B1A]"></strong>.
        </p>
        <p class="text-red-600 font-medium">
            Esta acción no se puede deshacer. El servicio dejará de estar disponible para reservas.
        </p>
    </x-confirm-modal>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Listado de Servicios</h2>
            <p class="text-sm text-gray-500 mt-1">Administra los servicios que ofreces en el salón.</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            {{-- FORMULARIO DE BÚSQUEDA --}}
            <form action="{{ route('admin.services.index') }}" method="GET" class="relative w-full sm:w-72">
                <div class="relative">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por servicio, precio, duración o estado..."
                        class="w-full pl-10 pr-10 py-2.5 rounded-full bg-white border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">

                    {{-- Icono de Lupa --}}
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Botón para limpiar búsqueda --}}
                    @if(request('search'))
                    <a href="{{ route('admin.services.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Servicio
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-gray-500 border-b border-[#EEE3DD]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Servicio</th>
                        <th class="px-6 py-4 font-semibold">Duración</th>
                        <th class="px-6 py-4 font-semibold">Precio</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-xl">
                                    {{ $service->icon }}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#1F1B1A]">{{ $service->name }}</p>
                                    <p class="text-xs text-gray-500 max-w-xs truncate">{{ $service->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $service->duration_minutes }} min
                        </td>
                        <td class="px-6 py-4 font-semibold text-pink-600">
                            ${{ number_format($service->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($service->active)
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Activo</span>
                            @else
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $service) }}" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Form oculto --}}
                                <form
                                    id="delete-service-form-{{ $service->id }}"
                                    action="{{ route('admin.services.destroy', $service) }}"
                                    method="POST"
                                    class="hidden"
                                    x-data
                                    x-on:confirmed-delete-service.window="
        if ($event.detail?.id === {{ $service->id }}) $el.submit()
    ">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                {{-- Botón --}}
                                <button
                                    type="button"
                                    x-data
                                    x-on:click="
        document.getElementById('delete-service-name').textContent = @js($service->name);
        $dispatch('open-confirm-delete-service', { id: {{ $service->id }} });
    "
                                    class="cursor-pointer p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Eliminar {{ $service->name }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            @if(request('search'))
                            {{-- CASO 1: Búsqueda sin resultados --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">No se encontraron servicios</p>
                                    <p class="text-sm text-gray-500 mt-1">No hay resultados para "{{ request('search') }}". Intenta con otra palabra.</p>
                                </div>
                                <a href="{{ route('admin.services.index') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-pink-600 hover:text-pink-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Limpiar búsqueda
                                </a>
                            </div>
                            @else
                            {{-- CASO 2: No hay servicios en la base de datos --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center text-pink-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">Aún no hay servicios</p>
                                    <p class="text-sm text-gray-500 mt-1">Comienza agregando tu primer servicio al catálogo.</p>
                                </div>
                                <a href="{{ route('admin.services.create') }}" class="mt-2 inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Crear mi primer servicio
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 👇 PAGINACIÓN 👇 --}}
        @if($services->hasPages())
        <div class="px-6 py-4 border-t border-[#EEE3DD]">
            {{ $services->links() }}
        </div>
        @endif
    </div>

</div>
@endsection