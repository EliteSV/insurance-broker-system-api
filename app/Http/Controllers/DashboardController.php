<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Aseguradora;
use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'polizasVigentes' => $this->handleDataRetrieval('countPolizasVigentes'),
            'pagosEnMora' => $this->handleDataRetrieval('countPagosEnMora'),
            'totalGanancias' => $this->handleDataRetrieval('calculateTotalGanancias'),
            'aseguradorasRegistradas' => $this->handleDataRetrieval('countAseguradorasRegistradas')
        ]);
    }

    protected function handleDataRetrieval($methodName, $parameters = [])
    {
        try {
            return call_user_func_array([$this, $methodName], $parameters);
        } catch (\Exception $e) {
            Log::error("Error in {$methodName}: " . $e->getMessage());
            return ['error' => "Failed to fetch data due to an internal error."];
        }
    }

    protected function countPolizasVigentes()
    {
        return Poliza::where('estado', 'Activa')->count();
    }

    protected function countPagosEnMora()
    {
        return Pagos::where('estado', 'En Mora')->count();
    }

    protected function calculateTotalGanancias()
    {
        $totalGanancia = 0;
        $polizas = Poliza::all();

        foreach ($polizas as $poliza) {
            $totalGanancia += $poliza->calculateGanancia();
        }

        return round($totalGanancia, 2);
    }

    protected function countAseguradorasRegistradas()
    {
        return Aseguradora::count();
    }
}
