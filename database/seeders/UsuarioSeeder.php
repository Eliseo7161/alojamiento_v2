<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Superadmin (Eliseo)
        User::create([
            'name' => 'Eliseo Villarroel',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('superadmin');

        // 2. Recepcionista
        User::create([
            'name' => 'Recepción Alojamiento',
            'email' => 'recepcion@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('recepcionistas');
    }
}