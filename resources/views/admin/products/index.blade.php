@extends('layouts.admin')

@section('title', 'Productos')
@section('page_title', 'Gestión de Productos')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Listado de Productos</h2>
            <p class="text-sm text-gray-500 mt-1">Administra el catálogo de productos de tu tienda.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            {{-- Formulario de búsqueda --}}
            <form action="{{ route('admin.products.index') }}" method="GET" class="relative w-full sm:w-72">
                <div class="relative">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por producto, marca, etc..."
                        class="w-full pl-10 pr-10 py-2.5 rounded-full bg-white border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">

                    {{-- Icono de Lupa --}}
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Botón para limpiar (si hay búsqueda) --}}
                    @if(request('search'))
                    <a href="{{ route('admin.products.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-gray-500 border-b border-[#EEE3DD]">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Producto</th>
                        <th class="px-6 py-4 font-semibold">Marca</th>
                        <th class="px-6 py-4 font-semibold">Precio</th>
                        <th class="px-6 py-4 font-semibold">Stock</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center overflow-hidden shrink-0 border border-[#EEE3DD]">
                                    @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                    <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-[#1F1B1A] truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 max-w-xs truncate">{{ $product->description ?? 'Sin descripción' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $product->brand ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->discount_price)
                            <div class="flex flex-col">
                                <span class="font-bold text-pink-600">${{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400 line-through">${{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            @else
                            <span class="font-bold text-[#1F1B1A]">${{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock > 5)
                            <span class="text-green-600 font-medium">{{ $product->stock }} un.</span>
                            @elseif($product->stock > 0)
                            <span class="text-yellow-600 font-medium">{{ $product->stock }} un. (Bajo)</span>
                            @else
                            <span class="text-red-600 font-medium">Agotado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->active)
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Activo</span>
                            @else
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
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
                        <td colspan="6" class="px-6 py-16 text-center">
                            @if(request('search'))
                            {{-- Mensaje de búsqueda sin resultados --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">No se encontraron productos</p>
                                    <p class="text-sm text-gray-500 mt-1">No hay resultados para "{{ request('search') }}". Intenta con otra palabra.</p>
                                </div>
                                <a href="{{ route('admin.products.index') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-pink-600 hover:text-pink-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Limpiar búsqueda
                                </a>
                            </div>
                            @else
                            {{-- Mensaje si no hay productos en la base de datos --}}
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center text-pink-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">Aún no hay productos</p>
                                    <p class="text-sm text-gray-500 mt-1">Comienza agregando tu primer producto al catálogo.</p>
                                </div>
                                <a href="{{ route('admin.products.create') }}" class="mt-2 inline-flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-full transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Crear mi primer producto
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
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-[#EEE3DD]">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection