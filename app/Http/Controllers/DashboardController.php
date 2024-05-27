<?php

namespace App\Http\Controllers;

use App\Models\Aseguradora;
use App\Models\Cliente;
use App\Models\TipoPoliza;
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
            'tiposDePolizas' => $this->handleDataRetrieval('countTiposDePoliza'),
            'clientesMora' => $this->handleDataRetrieval('countClientesEnMora'),
            'clientesAlDia' => $this->handleDataRetrieval('countClientesAlDia'),
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
        $estadoVigente = config('constants.polizaEstados.Vigente');
        return Poliza::where('estado', $estadoVigente)->count();
    }

    protected function countPolizasMora()
    {
        $estadoVencida = config('constants.polizaEstados.Vencida');
        return Poliza::where('estado', $estadoVencida)->count();
    }

    protected function countClientes()
    {

        return Cliente::count();
    }

    protected function countAseguradorasRegistradas()
    {
        return Aseguradora::count();
    }

    protected function countTiposDePoliza()
    {
        $tiposDePoliza = TipoPoliza::all();
        $result = [];

        foreach ($tiposDePoliza as $tipoPoliza) {
            $result[$tipoPoliza->nombre] = Poliza::where('tipo_poliza_id', $tipoPoliza->id)->count();
        }

        return $result;
    }

    protected function countClientesEnMora()
    {
        $estadoVencida = config('constants.polizaEstados.Vencida');
        return Cliente::whereHas('polizas', function ($query) use ($estadoVencida) {
            $query->where('estado', $estadoVencida);
        })->count();
    }

    protected function countClientesAlDia()
    {
        $estadoVigente = config('constants.polizaEstados.Vigente');
        return Cliente::whereHas('polizas', function ($query) use ($estadoVigente) {
            $query->where('estado', $estadoVigente);
        })->count();
    }
}
