@extends('layouts.main')

@section('title', 'Boleta ' . $order->code)

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="mx-auto px-4 sm:px-8">
        
        {{-- Botones de acción (ocultos al imprimir) --}}
        <div class="mb-6 flex justify-between items-center print:hidden">
            <a href="{{ route('home') }}"
                class="self-start shrink-0 group inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-pink-600 bg-white hover:bg-pink-50/70 border border-[#EEE3DD] hover:border-pink-200 px-3.5 py-2 rounded-full shadow-xs transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Volver al Inicio</span>
            </a>
            <button onclick="window.print()" class="cursor-pointer bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-2.5 rounded-full transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir / Guardar PDF
            </button>
        </div>

        {{-- Boleta --}}
        <div class="bg-white border border-[#EEE3DD] rounded-2xl p-8 sm:p-12 shadow-sm print:border-0 print:shadow-none print:p-0">
            
            {{-- Header --}}
            <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#1F1B1A]">Bella Conecta</h1>
                    <p class="text-sm text-gray-500 mt-1">Av. Principal 123, Santiago</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Boleta de Retiro</p>
                    <p class="text-xl font-mono text-pink-600 font-bold">{{ $order->code }}</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            {{-- Datos del cliente --}}
            <div class="mb-8">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Datos del Cliente</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Nombre</p>
                        <p class="font-semibold text-[#1F1B1A]">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="font-semibold text-[#1F1B1A]">{{ $order->customer_email }}</p>
                    </div>
                    @if($order->customer_phone)
                    <div>
                        <p class="text-xs text-gray-500">Teléfono</p>
                        <p class="font-semibold text-[#1F1B1A]">{{ $order->customer_phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Productos --}}
            <div class="mb-8">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Productos</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-gray-500">
                            <th class="pb-2 font-semibold">Producto</th>
                            <th class="pb-2 font-semibold text-center">Cant.</th>
                            <th class="pb-2 font-semibold text-right">Precio</th>
                            <th class="pb-2 font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 text-[#1F1B1A] font-medium">{{ $item->product_name }}</td>
                            <td class="py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                            <td class="py-3 text-right text-gray-600">${{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-3 text-right text-[#1F1B1A] font-semibold">${{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-gray-200">
                            <td colspan="3" class="pt-4 text-right font-bold text-[#1F1B1A]">Total a pagar en tienda:</td>
                            <td class="pt-4 text-right font-bold text-pink-600 text-lg">${{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-200 pt-8 mt-8 text-center">
                <p class="text-sm text-gray-500 mb-2">Presenta este código al retirar tus productos en la tienda física.</p>
                <div class="inline-block bg-pink-50 border border-pink-200 rounded-xl px-6 py-3">
                    <p class="font-mono text-xl font-bold text-pink-700 tracking-widest">{{ $order->code }}</p>
                </div>
                <p class="text-xs text-gray-400 mt-4">Este documento no es una factura. El pago se realiza en tienda.</p>
            </div>

        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    @media print {
        body { background: white !important; }
        section { padding: 0 !important; background: white !important; }
        .print\:hidden { display: none !important; }
        .bg-gray-50 { background: white !important; }
        .rounded-2xl { border-radius: 0 !important; }
    }
</style>
@endpush