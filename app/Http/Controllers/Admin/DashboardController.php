<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas básicas
        $reservasHoy = Booking::whereDate('date', today())->whereIn('status', ['pending', 'confirmed'])->count();
        $reservasSemana = Booking::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->whereIn('status', ['pending', 'confirmed'])->count();
        $totalServicios = Service::where('active', true)->count();
        $totalEstilistas = Employee::where('active', true)->count();

        // Ingresos estimados de hoy
        $ingresosHoy = Booking::whereDate('date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        // Últimas reservas (para mostrar una tabla rápida)
        $ultimasReservas = Booking::with(['user', 'service', 'employee'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'reservasHoy', 
            'reservasSemana', 
            'totalServicios', 
            'totalEstilistas', 
            'ingresosHoy',
            'ultimasReservas'
        ));
    }
}