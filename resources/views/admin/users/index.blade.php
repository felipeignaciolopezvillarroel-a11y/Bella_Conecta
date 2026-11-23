@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page_title', 'Gestión de Usuarios')

@section('content')
<div class="space-y-6">

    {{-- Modal de confirmación (uno solo para toda la tabla) --}}
    <x-confirm-modal
        name="delete-user"
        title="Eliminar usuario"
        confirm-text="Eliminar usuario"
        cancel-text="Cancelar"
        variant="danger">
        <p>
            Vas a eliminar al usuario
            <strong id="delete-user-name" class="text-[#1F1B1A]"></strong>.
        </p>
        <p class="text-red-600 font-medium">
            Esta acción no se puede deshacer. Se cancelarán sus reservas activas y se borrará su cuenta.
        </p>
    </x-confirm-modal>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Listado de Usuarios</h2>
            <p class="text-sm text-gray-500 mt-1">Clientes registrados sin empleado asociado.</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            {{-- FORMULARIO DE BÚSQUEDA --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-72">
                <div class="relative">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por nombre, email o reservas..."
                        class="w-full pl-10 pr-10 py-2.5 rounded-full bg-white border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-gray-500 border-b border-[#EEE3DD]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Usuario</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Reservas</th>
                        <th class="px-6 py-4 font-semibold">Registro</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-sm font-bold text-pink-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#1F1B1A]">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 max-w-xs truncate">{{ ucfirst($user->system_role) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-pink-50 text-pink-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                {{ $user->bookings_count }} {{ $user->bookings_count === 1 ? 'reserva' : 'reservas' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $user->created_at?->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->email_verified_at)
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Verificado</span>
                            @else
                            <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-gray-500 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition" title="Ver">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- Form oculto --}}
                                <form
                                    id="delete-user-form-{{ $user->id }}"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    method="POST"
                                    class="hidden"
                                    x-data
                                    x-on:confirmed-delete-user.window="
                                        if ($event.detail?.id === {{ $user->id }}) $el.submit()
                                    ">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                {{-- Botón --}}
                                <button
                                    type="button"
                                    x-data
                                    x-on:click="
                                        document.getElementById('delete-user-name').textContent = @js($user->name);
                                        $dispatch('open-confirm-delete-user', { id: {{ $user->id }} });
                                    "
                                    class="cursor-pointer p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Eliminar {{ $user->name }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            @if(request('search'))
                            {{-- CASO 1: Búsqueda sin resultados --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">No se encontraron usuarios</p>
                                    <p class="text-sm text-gray-500 mt-1">No hay resultados para "{{ request('search') }}". Intenta con otra palabra.</p>
                                </div>
                                <a href="{{ route('admin.users.index') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-pink-600 hover:text-pink-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Limpiar búsqueda
                                </a>
                            </div>
                            @else
                            {{-- CASO 2: No hay usuarios en la base de datos --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center text-pink-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zM20 8a3 3 0 100 6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">Aún no hay usuarios</p>
                                    <p class="text-sm text-gray-500 mt-1">Cuando alguien se registre aparecerá aquí.</p>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 👇 PAGINACIÓN 👇 --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-[#EEE3DD]">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection