<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagos;
use App\Models\Poliza;

class ReportesController extends Controller
{
    /**
     * Get Clientes with Mora
     */
    public function clientesConMora()
    {
        $pagosEnMora = Pagos::where('estado', 'Vencido')->with('vigencia.poliza.cliente')->get();

        $clientesConMora = $pagosEnMora->map(function ($pago) {
            return $pago->vigencia->poliza->cliente;
        })->unique('id');

        return response()->json($clientesConMora);
    }

    /**
     * GET Polizas Canceladas
     */
    public function polizasCanceladas()
    {
        $polizasCanceladas = Poliza::where('estado', 'Cancelada')->with(['cliente', 'aseguradora', 'tipoPoliza'])->get();

        return response()->json($polizasCanceladas);
    }

    /**
     * Get Polizas por Estados
     */
    public function polizasPorEstado()
    {
        $polizasPendiente = Poliza::where('estado', 'Pendiente')->with(['cliente', 'aseguradora', 'tipoPoliza'])->get();
        $polizasVigentes = Poliza::where('estado', 'Vigente')->with(['cliente', 'aseguradora', 'tipoPoliza'])->get();
        $polizasExpiradas = Poliza::where('estado', 'Expirada')->with(['cliente', 'aseguradora', 'tipoPoliza'])->get();
        $polizasCanceladas = Poliza::where('estado', 'Cancelada')->with(['cliente', 'aseguradora', 'tipoPoliza'])->get();

        $result = [
            'pendientes' => $polizasPendiente,
            'vigentes' => $polizasVigentes,
            'expiradas' => $polizasExpiradas,
            'canceladas' => $polizasCanceladas,
        ];

        return response()->json($result);
    }
}
