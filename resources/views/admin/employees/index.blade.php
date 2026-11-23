@extends('layouts.admin')

@section('title', 'Empleados')
@section('page_title', 'Gestión de Empleados')

@section('content')
<div class="space-y-6">

    {{-- Modal de confirmación --}}
    <x-confirm-modal
        name="delete-employee"
        title="Eliminar empleado"
        confirm-text="Eliminar empleado"
        cancel-text="Cancelar"
        variant="danger">
        <p>
            Vas a eliminar al empleado
            <strong id="delete-employee-name" class="text-[#1F1B1A]"></strong>.
        </p>
        <p class="text-red-600 font-medium">
            Esta acción no se puede deshacer. Las reservas asociadas no se borrarán, pero el empleado dejará de estar disponible.
        </p>
    </x-confirm-modal>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Listado de Empleados</h2>
            <p class="text-sm text-gray-500 mt-1">Administra a los profesionales y al personal administrativo.</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            {{-- FORMULARIO DE BÚSQUEDA --}}
            <form action="{{ route('admin.employees.index') }}" method="GET" class="relative w-full sm:w-72">
                <div class="relative">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar empleado, rol o categoría..."
                        class="w-full pl-10 pr-10 py-2.5 rounded-full bg-white border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">

                    {{-- Icono de Lupa --}}
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Botón para limpiar búsqueda --}}
                    @if(request('search'))
                    <a href="{{ route('admin.employees.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Empleado
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-gray-500 border-b border-[#EEE3DD]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Empleado</th>
                        <th class="px-6 py-4 font-semibold">Rol Sistema</th>
                        <th class="px-6 py-4 font-semibold">Categoría</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($employees as $employee)
                    @php
                    // Verificamos si el empleado es administrador
                    $isTargetAdmin = $employee->user && $employee->user->system_role === 'admin';
                    // Verificamos si el usuario actual es administrador (siempre lo será en el panel, pero por seguridad)
                    $isCurrentUserAdmin = auth()->user()->system_role === 'admin';
                    // Solo permitimos editar si no es admin o si YO soy admin
                    $canEdit = !$isTargetAdmin || $isCurrentUserAdmin;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-sm font-bold text-pink-600 overflow-hidden shrink-0">
                                    @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}" class="w-full h-full object-cover">
                                    @else
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-[#1F1B1A] truncate">{{ $employee->name }}</p>
                                    <p class="text-xs text-gray-500 max-w-xs truncate">{{ $employee->bio ?? 'Sin biografía' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($employee->user && $employee->user->system_role === 'admin')
                            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full">Administrador</span>
                            @else
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">Empleado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($employee->category === 'profesional')
                            <span class="text-pink-600 font-medium text-xs">Profesional</span>
                            @else
                            <span class="text-gray-500 text-xs">Administrativo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($employee->active)
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Activo</span>
                            @else
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Botón Editar (solo si tiene permisos) --}}
                                @if($canEdit)
                                <a href="{{ route('admin.employees.edit', $employee) }}" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Botón Eliminar (solo si tiene permisos) --}}
                                <form
                                    id="delete-employee-form-{{ $employee->id }}"
                                    action="{{ route('admin.employees.destroy', $employee) }}"
                                    method="POST"
                                    class="hidden"
                                    x-data
                                    x-on:confirmed-delete-employee.window="
                                        if ($event.detail?.id === {{ $employee->id }}) $el.submit()
                                    ">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button
                                    type="button"
                                    x-data
                                    x-on:click="
                                        document.getElementById('delete-employee-name').textContent = @js($employee->name);
                                        $dispatch('open-confirm-delete-employee', { id: {{ $employee->id }} });
                                    "
                                    class="cursor-pointer p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Eliminar {{ $employee->name }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @else
                                {{-- Si no tiene permisos, mostramos un candado --}}
                                <span class="p-2 text-gray-300 cursor-not-allowed" title="No tienes permisos para modificar a este administrador">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                @endif
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
                                    <p class="text-lg font-bold text-[#1F1B1A]">No se encontraron empleados</p>
                                    <p class="text-sm text-gray-500 mt-1">No hay resultados para "{{ request('search') }}". Intenta con otra palabra.</p>
                                </div>
                                <a href="{{ route('admin.employees.index') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-pink-600 hover:text-pink-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Limpiar búsqueda
                                </a>
                            </div>
                            @else
                            {{-- CASO 2: No hay empleados en la base de datos --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center text-pink-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">Aún no hay empleados</p>
                                    <p class="text-sm text-gray-500 mt-1">Comienza agregando a tu primer profesional o administrativo.</p>
                                </div>
                                <a href="{{ route('admin.employees.create') }}" class="mt-2 inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Crear mi primer empleado
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection