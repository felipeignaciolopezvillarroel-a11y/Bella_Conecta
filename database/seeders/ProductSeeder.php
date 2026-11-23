<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // --- CABELLO ---
            ['Shampoo Reparador de Keratina', "L'Oréal", 12000, null, 50, 'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=600&q=80', 'Shampoo profesional para cabello dañado y con frizz.'],
            ['Acondicionador Nutritivo', 'Kérastase', 15000, 12000, 30, 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=600&q=80', 'Acondicionador profundo con aceites esenciales.'],
            ['Mascarilla Capilar de Argán', 'Moroccanoil', 18000, null, 20, 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=600&q=80', 'Tratamiento intensivo para hidratación extrema.'],
            ['Aceite Capilar Ligero', 'Olaplex', 22000, null, 15, 'https://images.unsplash.com/photo-1615397349754-cfa2066a298e?w=600&q=80', 'Aceite para brillo y protección térmica.'],
            ['Sérum Antifrizz', 'Redken', 16000, null, 40, 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&q=80', 'Sérum para controlar el frizz y suavizar.'],
            ['Spray Fijador de Peinado', 'Schwarzkopf', 14000, null, 25, 'https://images.unsplash.com/photo-1599305090598-fe179d501227?w=600&q=80', 'Laca de fijación fuerte para peinados duraderos.'],
            ['Crema para Rizos', 'Cantu', 10000, null, 35, 'https://images.unsplash.com/photo-1585232004423-244e0e6904e3?w=600&q=80', 'Define y hidrata los rizos naturalmente.'],

            // --- UÑAS ---
            ['Esmalte en Gel Premium', 'OPI', 8000, null, 60, 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600&q=80', 'Esmalte de larga duración con acabado gel.'],
            ['Aceite de Cutículas', 'CND', 6000, null, 45, 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80', 'Aceite nutritivo para cutículas y uñas.'],
            ['Crema de Manos Reparadora', 'L\'Occitane', 9500, null, 50, 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=600&q=80', 'Hidratación intensa para manos secas.'],

            // --- MAQUILLAJE ---
            ['Base de Maquillaje HD', 'MAC', 25000, null, 30, 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&q=80', 'Base de cobertura media con acabado natural.'],
            ['Paleta de Sombras Nude', 'Urban Decay', 35000, 28000, 15, 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=600&q=80', '12 tonos neutros altamente pigmentados.'],
            ['Máscara de Pestañas Volumen', 'Maybelline', 12000, null, 80, 'https://images.unsplash.com/photo-1631214540553-ff044a3ff1ed?w=600&q=80', 'Máscara para pestañas de volumen extremo.'],
            ['Labial Mate Líquido', 'Anastasia Beverly Hills', 18000, null, 40, 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=600&q=80', 'Labial de larga duración con acabado mate.'],
            ['Iluminador en Polvo', 'Fenty Beauty', 20000, null, 25, 'https://images.unsplash.com/photo-1625093742435-6fa192b6fb10?w=600&q=80', 'Iluminador para un glow natural y radiante.'],

            // --- CUIDADO FACIAL ---
            ['Crema Hidratante Facial', 'CeraVe', 14000, null, 55, 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80', 'Hidratante con ácido hialurónico y ceramidas.'],
            ['Sérum de Vitamina C', 'The Ordinary', 16000, null, 30, 'https://images.unsplash.com/photo-1620916297397-a4a5402a3c6c?w=600&q=80', 'Sérum antioxidante para un tono uniforme.'],
            ['Protector Solar SPF 50', 'La Roche-Posay', 22000, null, 40, 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=600&q=80', 'Protección solar alta para uso diario.'],
            ['Agua Micelar', 'Bioderma', 12000, null, 60, 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=600&q=80', 'Limpia y desmaquilla sin irritar la piel.'],
            
            // --- PERFUMES ---
            ['Perfume Floral Eau de Parfum', 'Dior', 85000, 75000, 10, 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=600&q=80', 'Fragancia floral elegante y duradera.'],
        ];

        foreach ($productos as $index => $p) {
            Product::updateOrCreate(
                ['slug' => Str::slug($p[0])],
                [
                    'name' => $p[0],
                    'brand' => $p[1],
                    'price' => $p[2],
                    'discount_price' => $p[3],
                    'stock' => $p[4],
                    'image' => $p[5],
                    'description' => $p[6],
                    'icon' => '🧴', // Mantenemos el icono por defecto para evitar errores
                    'active' => true,
                    'featured' => $index < 4, // Los primeros 4 son destacados para el Home
                ]
            );
        }
    }
}