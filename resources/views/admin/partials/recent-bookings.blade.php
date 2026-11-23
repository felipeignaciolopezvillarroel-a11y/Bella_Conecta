<div class="bg-white border border-[#EEE3DD] rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-[#EEE3DD] flex items-center justify-between">
        <h2 class="text-lg font-bold text-[#1F1B1A]">Últimas reservas recibidas</h2>
        <a href="#" class="text-sm text-pink-600 hover:text-pink-800 font-semibold transition">Ver todas →</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-[#FDF8F5] text-gray-500">
                <tr>
                    <th class="px-6 py-4 font-semibold">Cliente</th>
                    <th class="px-6 py-4 font-semibold">Servicio</th>
                    <th class="px-6 py-4 font-semibold">Fecha y Hora</th>
                    <th class="px-6 py-4 font-semibold">Estilista</th>
                    <th class="px-6 py-4 font-semibold">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#EEE3DD]">
                @forelse($ultimasReservas as $reserva)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-[#1F1B1A]">{{ $reserva->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $reserva->user->email }}</p>
                    </td>
                    <td class="px-6 py-4">
                        {{ $reserva->service->icon }} {{ $reserva->service->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($reserva->date)->format('d/m/Y') }} <br>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $reserva->employee ? $reserva->employee->name : 'Cualquiera' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($reserva->status === 'confirmed')
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Confirmada</span>
                        @elseif($reserva->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-full">Pendiente</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2.5 py-1 rounded-full">Cancelada</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No hay reservas todavía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>