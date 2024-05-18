<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poliza;
use Illuminate\Support\Facades\Log;

class PolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Poliza::with(['cliente', 'aseguradora', 'tipoPoliza', 'vigencias'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|max:255',
                'estado' => 'required|max:255',
                'monto' => 'required|numeric',
                'cuotas' => 'required|integer',
                'detalles' => 'required|array',
                'fecha_inicio' => 'required|max:255',
                'fecha_vencimiento' => 'required|max:255',
                'cliente_id' => 'required|exists:clientes,id',
                'aseguradora_id' => 'required|exists:aseguradoras,id',
                'tipo_poliza_id' => 'required|exists:tipo_polizas,id'
            ]);

            $polizaData = $request->all();
            $polizaData['detalles'] = json_encode($request->detalles);

            $poliza = Poliza::create($polizaData);

            $poliza->vigencias()->create([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_vencimiento' => $request->fecha_vencimiento,
            ]);
            return response()->json($poliza);
        } catch (\Exception $e) {
            Log::error('Failed to create poliza: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $poliza = Poliza::with(['cliente', 'aseguradora', 'tipoPoliza', 'vigencias'])->findOrFail($id);
        return $poliza;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|max:255',
            'estado' => 'sometimes|required|max:255',
            'monto' => 'sometimes|required|numeric',
            'cuotas' => 'sometimes|required|integer',
            'detalles' => 'sometimes|required|array',
            'cliente_id' => 'sometimes|required|exists:clientes,id',
            'aseguradora_id' => 'sometimes|required|exists:aseguradoras,id',
            'tipo_poliza_id' => 'sometimes|required|exists:tipo_polizas,id'
        ]);

        $poliza = Poliza::findOrFail($id);

        if ($request->has('detalles')) {
            $request->merge(['detalles' => json_encode($request->detalles)]);
        }

        $poliza->update($request->all());
        return $poliza;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Poliza::destroy($id);
    }
}
