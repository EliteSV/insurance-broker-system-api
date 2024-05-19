<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagos;
use App\Services\FileService;
use Illuminate\Support\Facades\Log;

class PagosController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Pagos::with("vigencia.poliza", "vigencia.poliza.cliente",  "vigencia.poliza.aseguradora",  "vigencia.poliza.tipoPoliza",  "vigencia.poliza.vigencias")->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'vigencia_poliza_id' => 'required|exists:vigencia_polizas,id',
                'cantidad' => 'required|numeric',
                'fecha_vencimiento' => 'required|max:255',
                'fecha_pagado' => 'nullable|max:255',
                'comprobante' => 'nullable|file',
                'estado' => 'required|max:255',
            ]);

            $comprobante = $request->file('comprobante');

            $url = null;
            if ($request->hasFile('comprobante')) {
                $comprobante = $request->file('comprobante');
                $path = "Pagos/{$request->vigencia_poliza_id}";
                $url = $this->fileService->uploadFile($comprobante, $path);
            }


            $pago = new Pagos([
                'vigencia_poliza_id' => $request->vigencia_poliza_id,
                'cantidad' => $request->cantidad,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'fecha_pagado' => $request->fecha_pagado,
                'comprobante' => $url,
                'estado' => $request->estado,
            ]);
            $pago->save();

            return response()->json($pago);
        } catch (\Exception $e) {
            Log::error('Failed to store Pago: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Pagos::with("vigencia.poliza", "vigencia.poliza.cliente",  "vigencia.poliza.aseguradora",  "vigencia.poliza.tipoPoliza",  "vigencia.poliza.vigencias")->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $pago = Pagos::findOrFail($id);

            $request->validate([
                'fecha_pagado' => 'nullable|max:255',
                'comprobante' => 'nullable|file',
                'estado' => 'required|max:255',
            ]);

            if ($request->hasFile('comprobante')) {
                $comprobante = $request->file('comprobante');
                $path = "Pagos/{$pago->vigencia_poliza_id}";
                $url = $this->fileService->uploadFile($comprobante, $path);
                $pago->comprobante = $url;
            }

            $pago->fecha_pagado = $request->fecha_pagado;
            $pago->estado = $request->estado;
            $pago->save();

            $remainingEnMora = Pagos::where('vigencia_poliza_id', $pago->vigencia_poliza_id)
                ->where('estado', 'En Mora')
                ->count();

            if ($remainingEnMora == 0) {
                $poliza = $pago->vigencia->poliza;
                $poliza->estado = 'Activa';
                $poliza->save();
            }

            return response()->json($pago);
        } catch (\Exception $e) {
            Log::error('Failed to update Pago: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pagos::destroy($id);
    }
}
