<?php

namespace Database\Seeders;

use App\Models\TiposDocumentos;
use Illuminate\Database\Seeder;

class TiposDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Dui',
            'Nit',
            'Poliza',
            'Otros'
        ];

        foreach ($tipos as $tipo) {
            TiposDocumentos::create([
                'nombre' => $tipo
            ]);
        }
    }
}
