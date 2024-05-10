<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Rol::create([
            'nombre' => 'Admin',
        ]);

        Rol::create([
            'nombre' => 'Gerente',
        ]);

        Rol::create([
            'nombre' => 'Agente',
        ]);
    }
}
