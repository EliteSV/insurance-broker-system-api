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
            'weeks' => 'required|integer|min:1|max:4',
        ]);

        $weeks = (int)$validatedData['weeks'];

        $currentDate = Carbon::now('GMT');
        $endDate = $currentDate->copy()->addWeeks($weeks);

        $formattedCurrentDate = $currentDate->toDateString();
        $formattedEndDate = $endDate->toDateString();

        $vigencias = VigenciaPolizas::with('poliza', 'poliza.aseguradora')
            ->whereBetween('fecha_vencimiento', [$formattedCurrentDate, $formattedEndDate])
            ->get();

        return response()->json($vigencias);
    }
}
