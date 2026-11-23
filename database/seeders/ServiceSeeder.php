<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            // --- CABELLO ---
            ['Corte de cabello', '💇', 45, 25000, 'Corte personalizado según tu rostro y estilo de vida.'],
            ['Corte de cabello + Lavado', '🚿', 60, 28000, 'Corte personalizado con lavado y secado profesional.'],
            ['Coloración completa', '🎨', 120, 45000, 'Tinte profesional de raíz a puntas con productos premium.'],
            ['Balayage / Highlights', '✨', 180, 65000, 'Técnica de iluminación natural para un look moderno.'],
            ['Mechas / Reflejos', '🌟', 150, 55000, 'Mechas personalizadas según tu tono de piel y estilo.'],
            ['Keratina / Alisado', '🌪️', 120, 70000, 'Tratamiento alisador con keratina para un cabello liso y brillante.'],
            ['Hidratación profunda', '💧', 60, 35000, 'Tratamiento intensivo para reparar y nutrir el cabello dañado.'],
            ['Peinado de novia', '👰', 120, 80000, 'Peinado y recogido especial para tu día de boda.'],
            ['Peinado para evento', '🎉', 60, 40000, 'Peinados y ondas para fiestas, graduaciones o eventos especiales.'],

            // --- UÑAS ---
            ['Manicure tradicional', '💅', 45, 15000, 'Limpieza, limado y esmaltado clásico.'],
            ['Manicure en gel', '💎', 60, 25000, 'Esmalte en gel de larga duración con secado UV.'],
            ['Uñas acrílicas', '🖌️', 90, 35000, 'Extensión de uñas acrílicas con el diseño que prefieras.'],
            ['Pedicure completo', '🦶', 60, 20000, 'Cuidado completo de pies con exfoliación y esmaltado.'],
            ['Spa de pies', '🛁', 75, 28000, 'Tratamiento relajante de pies con exfoliación y masaje.'],
            ['Diseño de uñas', '🎨', 30, 10000, 'Diseño artístico personalizado en tus uñas.'],

            // --- MAQUILLAJE ---
            ['Maquillaje social', '💄', 60, 30000, 'Maquillaje para fiestas, cenas y eventos sociales.'],
            ['Maquillaje de novia', '💍', 90, 60000, 'Maquillaje profesional de larga duración para tu boda.'],
            ['Maquillaje para fotos', '📸', 60, 35000, 'Maquillaje especial para sesiones fotográficas.'],

            // --- FACIAL / ESTÉTICA ---
            ['Limpieza facial profunda', '🧖', 60, 25000, 'Limpieza con extracción de impurezas y mascarilla.'],
            ['Depilación con cera', '🪒', 30, 12000, 'Depilación de cejas, labio superior o axilas.'],
        ];

        foreach ($servicios as $index => $s) {
            Service::updateOrCreate(
                ['slug' => Str::slug($s[0])],
                [
                    'name' => $s[0],
                    'icon' => $s[1],
                    'duration_minutes' => $s[2],
                    'price' => $s[3],
                    'description' => $s[4],
                    'active' => true,
                    'featured' => $index < 3, // Los primeros 3 son destacados
                    'features' => [],
                ]
            );
        }
    }
}