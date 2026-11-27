@extends('layouts.admin')

@section('title', 'Reseñas')
@section('page_title', 'Gestión de Reseñas')

@section('content')
<div class="space-y-6">

    {{-- Modal de confirmación para eliminar --}}
    <x-confirm-modal
        name="delete-review"
        title="Eliminar reseña"
        confirm-text="Eliminar reseña"
        cancel-text="Cancelar"
        variant="danger">
        <p>Vas a eliminar la reseña de <strong id="delete-review-name" class="text-[#1F1B1A]"></strong>.</p>
        <p class="text-red-600 font-medium">Esta acción no se puede deshacer.</p>
    </x-confirm-modal>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Listado de Reseñas</h2>
            <p class="text-sm text-gray-500 mt-1">Aprueba o elimina las reseñas de tus clientas.</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            {{-- Búsqueda --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o texto de la reseña..."
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
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Todas</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobadas</option>
                </select>
            </div>

            {{-- Botones --}}
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm text-sm cursor-pointer">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.reviews.index') }}" class="w-full sm:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm cursor-pointer">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#FDF8F5] text-gray-500 border-b border-[#EEE3DD]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Cliente</th>
                        <th class="px-6 py-4 font-semibold">Reseña</th>
                        <th class="px-6 py-4 font-semibold">Calificación</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#1F1B1A]">{{ $review->name }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                            @if($review->service)
                                <p class="text-xs text-pink-600 mt-1">Servicio: {{ $review->service }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <p class="text-sm text-gray-700 line-clamp-3">{{ $review->review }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->approved)
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Aprobada</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-full">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$review->approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition cursor-pointer" title="Aprobar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="p-2 text-green-600" title="Ya aprobada">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                @endif

                                {{-- Formulario oculto para eliminar --}}
                                <form
                                    id="delete-review-form-{{ $review->id }}"
                                    action="{{ route('admin.reviews.destroy', $review) }}"
                                    method="POST"
                                    class="hidden"
                                    x-data
                                    x-on:confirmed-delete-review.window="
                                        if ($event.detail?.id === {{ $review->id }}) $el.submit()
                                    ">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                {{-- Botón de eliminar --}}
                                <button
                                    type="button"
                                    x-data
                                    x-on:click="
                                        document.getElementById('delete-review-name').textContent = @js($review->name);
                                        $dispatch('open-confirm-delete-review', { id: {{ $review->id }} });
                                    "
                                    class="cursor-pointer p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Eliminar reseña">
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
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">No hay reseñas</p>
                                    <p class="text-sm text-gray-500 mt-1">Las reseñas de los clientes aparecerán aquí.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-[#EEE3DD] bg-[#FDF8F5]">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>
@endsection