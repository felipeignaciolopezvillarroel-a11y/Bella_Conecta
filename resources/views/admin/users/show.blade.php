@extends('layouts.admin')

@section('title', 'Usuario · ' . $user->name)
@section('page_title', 'Detalle de Usuario')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-pink-50 flex items-center justify-center text-lg font-bold text-pink-600">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-[#1F1B1A]">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-pink-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid sm:grid-cols-3 gap-4">
        <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Reservas totales</p>
            <p class="text-3xl font-bold text-[#1F1B1A]">{{ $user->bookings_count }}</p>
        </div>
        <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Registro</p>
            <p class="text-sm font-semibold text-[#1F1B1A]">{{ $user->created_at?->format('d/m/Y H:i') }}</p>
        </div>
        <div class="bg-white border border-[#EEE3DD] rounded-2xl p-6 shadow-sm">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rol</p>
            <p class="text-sm font-semibold text-[#1F1B1A]">{{ ucfirst($user->system_role) }}</p>
        </div>
    </div>

    {{-- Reservas --}}
    <div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EEE3DD]">
            <h3 class="font-bold text-[#1F1B1A]">Últimas reservas</h3>
        </div>

        @if($bookings->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-[#1F1B1A]">Sin reservas</p>
                        <p class="text-sm text-gray-500 mt-1">Este usuario aún no ha hecho reservas.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-gray-500 border-b border-[#EEE3DD]">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Servicio</th>
                            <th class="px-6 py-4 font-semibold">Fecha</th>
                            <th class="px-6 py-4 font-semibold">Estilista</th>
                            <th class="px-6 py-4 font-semibold">Estado</th>
                            <th class="px-6 py-4 font-semibold">Código</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EEE3DD]">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center text-xl">
                                        {{ $booking->service->icon }}
                                    </div>
                                    <p class="font-semibold text-[#1F1B1A]">{{ $booking->service->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $booking->date->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $booking->employee?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusMap = [
                                        'cancelled' => ['label' => 'Cancelada', 'class' => 'bg-gray-100 text-gray-700'],
                                        'pending'   => ['label' => 'Pendiente', 'class' => 'bg-amber-100 text-amber-700'],
                                        'confirmed' => ['label' => 'Confirmada', 'class' => 'bg-green-100 text-green-700'],
                                        'completed' => ['label' => 'Completada', 'class' => 'bg-green-100 text-green-700'],
                                    ];
                                    $st = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-700'];
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $st['class'] }}">{{ $st['label'] }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-pink-600">{{ $booking->code }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection