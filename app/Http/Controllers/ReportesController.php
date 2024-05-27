<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Poliza;

class ReportesController extends Controller
{
    private $polizaEstados;
    private $pagoEstados;

    public function __construct()
    {
        $this->polizaEstados = config('constants.polizaEstados');
        $this->pagoEstados = config('constants.pagoEstados');
    }

    /**
     * Get Clientes with Mora
     */
    public function clientesConMora()
    {
        $estadoVencido = $this->pagoEstados['Vencido'];

        $pagosEnMora = Pagos::where('estado', $estadoVencido)->with('vigencia.poliza.cliente')->get();

        $clientesConMora = $pagosEnMora->map(function ($pago) {
            return $pago->vigencia->poliza->cliente;
        })->unique('id')->values();

        return response()->json($clientesConMora);
    }

    /**
     * GET Polizas Canceladas
     */
    public function polizasCanceladas()
    {
        $estadoCancelada = $this->polizaEstados['Cancelada'];

        $polizasCanceladas = Poliza::where('estado', $estadoCancelada)->with(['cliente', 'aseguradora', 'tipoPoliza'])->get();

        return response()->json($polizasCanceladas);
    }

    /**
     * Get Polizas por Estados
     */
    public function polizasPorEstado()
    {
        $result = [];

        foreach ($this->polizaEstados as $estado) {
            $key = strtolower($estado) . 's';
            $polizas = Poliza::where('estado', $estado)
                ->with(['cliente', 'aseguradora', 'tipoPoliza'])
                ->get();
            $result[$key] = $polizas;
        }

        return response()->json($result);
    }
}
