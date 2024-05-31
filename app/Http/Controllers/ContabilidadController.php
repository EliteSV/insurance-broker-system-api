<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poliza;

class ContabilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $polizas = Poliza::orderBy('created_at', 'desc')->get();

        $totalGanancia = 0;
        $polizas->transform(function ($poliza) use (&$totalGanancia) {
            $ganancia = $poliza->calculateGanancia();
            $poliza->ganancia = $ganancia;
            $totalGanancia += $ganancia;
            return $poliza;
        });

        $response = [
            'totalGanancia' => round($totalGanancia, 2),
            'polizas' => $polizas
        ];

        return response()->json($response);
    }
}
