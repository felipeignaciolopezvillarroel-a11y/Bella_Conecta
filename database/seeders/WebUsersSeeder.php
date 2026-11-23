<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WebUsersSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Ana García', 'Luis Martínez', 'Carmen Rodríguez', 'Javier López',
            'Lucía Fernández', 'Diego Sánchez', 'Marta Pérez', 'Pablo Gómez',
            'Elena Ruiz', 'Sergio Díaz',
        ];

        foreach ($names as $i => $name) {
            $email = Str::slug($name, '.') . '@example.com';

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name'        => $name,
                    'password'    => Hash::make('password'),
                    'system_role' => 'usuario',
                ]
            );
        }

        $this->command->info('10 usuarios web creados (password: "password").');
    }
}