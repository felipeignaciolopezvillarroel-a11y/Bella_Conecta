@extends('layouts.portal')

@section('title', 'Órdenes para Retirar')
@section('page_title', 'Recepción - Órdenes de Retiro')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1F1B1A]">Órdenes de Retiro</h2>
            <p class="text-sm text-gray-500 mt-1">Gestiona las voletas pendientes de retiro en tienda.</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl p-4 shadow-sm">
        <form action="{{ route('portal.receptionist.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            
            {{-- Búsqueda --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Código o nombre del cliente..."
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
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Retiradas</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todas</option>
                </select>
            </div>

            {{-- Botones --}}
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm text-sm cursor-pointer">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('portal.receptionist.index') }}" class="w-full sm:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm cursor-pointer">
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
                        <th class="px-6 py-4 font-semibold">Código</th>
                        <th class="px-6 py-4 font-semibold">Cliente</th>
                        <th class="px-6 py-4 font-semibold">Productos</th>
                        <th class="px-6 py-4 font-semibold">Total</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEE3DD]">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-pink-600">
                            {{ $order->code }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#1F1B1A]">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_email }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="space-y-1">
                                @foreach($order->items as $item)
                                    <li class="text-xs">
                                        <span class="font-semibold text-[#1F1B1A]">{{ $item->quantity }}x</span>
                                        {{ $item->product_name }}
                                        <span class="text-pink-600">(${{ number_format($item->price, 0, ',', '.') }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#1F1B1A]">
                            ${{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status === 'pending')
                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">Pendiente</span>
                            @elseif($order->status === 'delivered')
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">Retirada</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">Cancelada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($order->status === 'pending')
                                <form action="{{ route('portal.receptionist.deliver', $order) }}" method="POST" onsubmit="return confirm('¿Confirmas que el cliente retiró esta orden?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-full text-xs transition shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Entregar
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">
                                    Entregada por {{ $order->deliveredBy->name ?? '—' }}<br>
                                    {{ $order->delivered_at?->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#1F1B1A]">No hay órdenes</p>
                                    <p class="text-sm text-gray-500 mt-1">Las voletas de retiro aparecerán aquí.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-[#EEE3DD] bg-[#FDF8F5]">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection