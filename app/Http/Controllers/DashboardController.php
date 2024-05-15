<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Cliente;
use App\Models\Aseguradora;
use App\Models\Poliza;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $pageSize = $request->input('pageSize', 10);

        return response()->json([
            'pagosVencidos' => $this->handleDataRetrieval('getPagosVencidos', [$pageSize]),
            'clientes' => $this->handleDataRetrieval('getEntityData', [Cliente::class, $pageSize]),
            'aseguradoras' => $this->handleDataRetrieval('getEntityData', [Aseguradora::class, $pageSize]),
            'polizas' => $this->handleDataRetrieval('getPolizasWithRelations', [$pageSize]),
        ]);
    }

    protected function handleDataRetrieval($methodName, $parameters)
    {
        try {
            return call_user_func_array([$this, $methodName], $parameters);
        } catch (\Exception $e) {
            Log::error("Error in {$methodName}: " . $e->getMessage());
            return ['error' => "Failed to fetch data due to an internal error."];
        }
    }

    protected function getPagosVencidos($pageSize)
    {
        return Pagos::where('fecha_vencimiento', '<', Carbon::today('GMT')->toDateString())
            ->paginate($pageSize)
            ->transform(function ($pago) {
                $fechaVencimiento = Carbon::parse($pago->fecha_vencimiento, 'GMT')->startOfDay();
                $today = Carbon::now('GMT')->startOfDay();
                $pago->diasRetraso = abs($fechaVencimiento->diffInDays($today, false));
                return $pago;
            });
    }

    protected function getEntityData($model, $pageSize)
    {
        $data = $model::paginate($pageSize);
        return [
            'totalRecords' => $data->total(),
            'data' => $data
        ];
    }

    protected function getPolizasWithRelations($pageSize)
    {
        $polizas = Poliza::with('cliente', 'aseguradora', 'tipoPoliza', 'vigencias')->paginate($pageSize);
        return [
            'totalRecords' => $polizas->total(),
            'data' => $polizas
        ];
    }
}
