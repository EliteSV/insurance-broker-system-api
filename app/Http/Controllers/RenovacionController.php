<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VigenciaPolizas;
use Illuminate\Support\Facades\Log;

class RenovacionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'fecha_inicio' => 'required|max:255',
                'fecha_vencimiento' => 'required|max:255',
                'poliza_id' => 'required|exists:polizas,id'
            ]);

            $vigenciaData = $request->all();

            return VigenciaPolizas::create($vigenciaData);
        } catch (\Exception $e) {
            Log::error('Failed to store renovacion: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
