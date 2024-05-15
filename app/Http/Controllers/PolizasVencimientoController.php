<?php

namespace App\Http\Controllers;

use App\Models\VigenciaPolizas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PolizasVencimientoController extends Controller
{
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
        ]);

        $referenceDate = Carbon::parse($validatedData['date'], 'GMT');
        $formattedReferenceDate = $referenceDate->toDateString();

        $vigencias = VigenciaPolizas::with('poliza')
            ->where('fecha_vencimiento', '>', $formattedReferenceDate)
            ->get();

        return response()->json($vigencias);
    }
}
