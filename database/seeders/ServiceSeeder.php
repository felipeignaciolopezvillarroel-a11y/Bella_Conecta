<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Stylist;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Corte de cabello',    'corte',       '💇', 45,  25000],
            ['Coloración',          'coloracion',  '🎨', 90,  45000],
            ['Manicure & Pedicure', 'manicure',    '💅', 60,  18000],
            ['Tratamiento capilar', 'tratamiento', '✨', 60,  35000],
            ['Maquillaje',          'maquillaje',  '💄', 60,  40000],
            ['Peinado de novia',    'novia',       '👰', 120, 60000],
        ];

        foreach ($services as [$name, $slug, $icon, $dur, $price]) {
            Service::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'icon' => $icon,
                'duration_minutes' => $dur, 'price' => $price, 'active' => true,
            ]);
        }

        $stylists = [
            ['Sofía Ramírez',    'Colorista & Balayage'],
            ['Valentina Torres', 'Especialista en cortes'],
            ['Camila Pérez',     'Peinados & Novias'],
        ];

        foreach ($stylists as [$name, $role]) {
            Stylist::updateOrCreate(['name' => $name], ['role' => $role, 'active' => true]);
        }
    }
}