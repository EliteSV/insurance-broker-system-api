<?php

namespace App\Http\Controllers;

use App\Models\Aseguradora;
use App\Models\Cliente;
use App\Models\Poliza;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'polizasVigentes' => $this->handleDataRetrieval('countPolizasVigentes'),
            'polizasMora' => $this->handleDataRetrieval('countPolizasMora'),
            'totalClientes' => $this->handleDataRetrieval('countClientes'),
            'aseguradorasRegistradas' => $this->handleDataRetrieval('countAseguradorasRegistradas'),
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

    protected function countPolizasMora()
    {
        return Poliza::where('estado', 'En Mora')->count();
    }

    protected function countClientes()
    {

        return Cliente::count();
    }

    protected function countAseguradorasRegistradas()
    {
        return Aseguradora::count();
    }
}
