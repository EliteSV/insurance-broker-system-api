<?php

namespace App\Http\Controllers;

use App\Models\Poliza;
use App\Models\VigenciaPolizas;
use App\Services\PolizaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RenovacionController extends Controller
{
    protected $polizaService;

    public function __construct(PolizaService $polizaService)
    {
        $this->polizaService = $polizaService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_vencimiento' => 'required|date',
                'poliza_id' => 'required|exists:polizas,id'
            ]);

            $vigenciaData = $request->all();
            $vigenciaPoliza = VigenciaPolizas::create($vigenciaData);

            $poliza = Poliza::find($vigenciaData['poliza_id']);
            $this->polizaService->createPagos($vigenciaPoliza, $poliza->monto, $request->fecha_inicio, $poliza->cuotas);
            $poliza->estado = 'Vigente';
            $poliza->save();

            $vigenciaPoliza->load(['pagos']);

            return response()->json($vigenciaPoliza, 201);
        } catch (\Exception $e) {
            Log::error('Failed to store renovacion: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
