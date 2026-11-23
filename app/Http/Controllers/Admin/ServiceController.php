<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Buscamos en Nombre, Duración y Precio
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('duration_minutes', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");
                  
                // Lógica especial para el Estado (porque se guarda como 1 o 0)
                if (str_contains(strtolower($search), 'activ')) {
                    $q->orWhere('active', true);
                }
                if (str_contains(strtolower($search), 'inactiv')) {
                    $q->orWhere('active', false);
                }
            });
        }

        $services = $query->orderBy('name')->paginate(8)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:10',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'active' => 'boolean',
            'featured' => 'boolean',
            'features' => 'nullable|array',
            'features.*.title' => 'required|string',
            'features.*.subtitle' => 'nullable|string',
        ], [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'icon.required' => 'El icono (emoji) es obligatorio.',
            'duration_minutes.required' => 'La duración es obligatoria.',
            'duration_minutes.min' => 'La duración debe ser al menos 1 minuto.',
            'price.required' => 'El precio es obligatorio.',
            'price.min' => 'El precio no puede ser negativo.',
            'features.*.title.required' => 'El título del beneficio es obligatorio.',
        ]);

        // Solo las claves válidas del modelo
        $data = collect($data)->only([
            'name',
            'description',
            'icon',
            'duration_minutes',
            'price',
            'active',
            'featured',
            'features',
        ])->all();

        $data['slug'] = Str::slug($request->name);
        $data['active'] = $request->has('active');
        $data['featured'] = $request->has('featured');

        // Formateo de features AQUÍ (antes estaba en el mutador del modelo)
        $data['features'] = collect($data['features'] ?? [])
            ->filter(fn($feature) => is_array($feature) && !empty($feature['title']))
            ->map(fn($feature) => [
                'title'    => ucfirst(trim($feature['title'])),
                'subtitle' => ucfirst(trim($feature['subtitle'] ?? '')),
            ])
            ->values()
            ->toArray();

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:10',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'active' => 'boolean',
            'featured' => 'boolean',
            'features' => 'nullable|array',
            'features.*.title' => 'required|string',
            'features.*.subtitle' => 'nullable|string',
        ], [
            'name.required' => 'El nombre del servicio es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'icon.required' => 'El icono (emoji) es obligatorio.',
            'duration_minutes.required' => 'La duración es obligatoria.',
            'duration_minutes.min' => 'La duración debe ser al menos 1 minuto.',
            'price.required' => 'El precio es obligatorio.',
            'price.min' => 'El precio no puede ser negativo.',
            'features.*.title.required' => 'El título del beneficio es obligatorio.',
        ]);

        $data = collect($data)->only([
            'name',
            'description',
            'icon',
            'duration_minutes',
            'price',
            'active',
            'featured',
            'features',
        ])->all();

        $data['slug'] = Str::slug($request->name);
        $data['active'] = $request->has('active');
        $data['featured'] = $request->has('featured');

        $data['features'] = collect($data['features'] ?? [])
            ->filter(fn($feature) => is_array($feature) && !empty($feature['title']))
            ->map(fn($feature) => [
                'title'    => ucfirst(trim($feature['title'])),
                'subtitle' => ucfirst(trim($feature['subtitle'] ?? '')),
            ])
            ->values()
            ->toArray();

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}
