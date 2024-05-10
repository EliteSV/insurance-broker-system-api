<?php

namespace Database\Seeders;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Admin',
            'email' => 'admin@adminseguros360.cloud',
            'password' => Hash::make('admin'),
            'rol_id' => 1
        ]);
    }
}
