<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Employee;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('active', true)->orderBy('name')->get();
        $quickServices = $services->sortBy('price')->take(3)->values();

        // Equipo profesional para el carrusel y la página /equipo
        $team = \App\Models\Employee::where('active', true)
            ->where('category', 'profesional')
            ->orderBy('name')
            ->take(6)        
            ->get();

        return view('pages.home', compact('services', 'quickServices', 'team'));
    }
}
