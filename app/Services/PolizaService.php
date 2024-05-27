<?php

namespace App\Services;

use App\Models\Pagos;
use App\Models\VigenciaPolizas;
use Carbon\Carbon;

class PolizaService
{
    public function createPagos(VigenciaPolizas $vigenciaPoliza, $monto, $fechaInicio, $cuotas)
    {
        $parsedDate = Carbon::parse($fechaInicio);

        $intervalLoopMap = [
            1 => [12, 0],
            2 => [6, 1],
            4 => [3, 3],
            12 => [1, 11]
        ];

        [$interval, $iterations] = $intervalLoopMap[$cuotas];

        $cantidad = round($monto / ($iterations + 1), 2);
        $cuotaNo = 1;
        $estadoPendiente = config('constants.estadosComunes.Pendiente');

        Pagos::create([
            'vigencia_poliza_id' => $vigenciaPoliza->id,
            'cantidad' => $cantidad,
            'fecha_vencimiento' => $parsedDate->format('Y-m-d'),
            'fecha_pagado' => null,
            'cuota' => $cuotaNo,
            'comprobante' => null,
            'estado' => $estadoPendiente
        ]);

        for ($i = 1; $i <= $iterations; $i++) {
            $fechaVencimiento = $parsedDate->copy()->addMonths($interval * $i)->format('Y-m-d');

            Pagos::create([
                'vigencia_poliza_id' => $vigenciaPoliza->id,
                'cantidad' => $cantidad,
                'cuota' => $cuotaNo + $i,
                'fecha_vencimiento' => $fechaVencimiento,
                'fecha_pagado' => null,
                'comprobante' => null,
                'estado' => $estadoPendiente
            ]);
        }
    }
}
