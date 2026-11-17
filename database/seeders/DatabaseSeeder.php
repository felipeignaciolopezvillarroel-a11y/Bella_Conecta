<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aquí cargamos SOLO los servicios y estilistas reales
        $this->call([
            ServiceSeeder::class,
        ]);
    }
}