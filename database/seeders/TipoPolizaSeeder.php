<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPoliza;

class TipoPolizaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $policyTypes = [
            'Incendios',
            'Automovil',
            'Medico',
            'Vida'
        ];

        foreach ($policyTypes as $policyType) {
            TipoPoliza::create([
                'nombre' => $policyType
            ]);
        }
    }
}
