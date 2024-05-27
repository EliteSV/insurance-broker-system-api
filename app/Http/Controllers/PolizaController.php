<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poliza;
use App\Models\VigenciaPoliza;
use Illuminate\Support\Facades\Log;
use App\Services\PolizaService;
use Carbon\Carbon;

class PolizaController extends Controller
{
    protected $polizaService;

    public function __construct(PolizaService $polizaService)
    {
        $this->polizaService = $polizaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Poliza::with(['cliente', 'aseguradora', 'tipoPoliza', 'vigencias', 'vigencias.pagos'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validatePoliza($request);

            $poliza = $this->createPoliza($request);
            $vigenciaPoliza = $this->createVigenciaPoliza($poliza, $request);
            $this->polizaService->createPagos($vigenciaPoliza, $poliza->monto, $request->fecha_inicio, $poliza->cuotas);

            $poliza->load(['vigencias.pagos']);

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
        $poliza = Poliza::with(['cliente', 'aseguradora', 'tipoPoliza', 'vigencias', 'vigencias.pagos'])->findOrFail($id);
        return $poliza;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $poliza = Poliza::findOrFail($id);
        $this->validatePoliza($request, $poliza->id);

        try {
            $this->updatePoliza($poliza, $request);

            if ($request->has(['fecha_inicio', 'fecha_vencimiento'])) {
                $this->updateVigenciaPoliza($poliza, $request);
            }

            $poliza->load(['vigencias.pagos']);

            return response()->json($poliza);
        } catch (\Exception $e) {
            Log::error('Failed to update poliza: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Poliza::destroy($id);
    }

    private function validatePoliza(Request $request, $polizaId = null)
    {
        $rules = [
            'nombre' => 'required|max:255',
            'estado' => 'required|max:255',
            'codigo' => 'required|max:255',
            'monto' => 'required|numeric',
            'cuotas' => 'required|integer|in:1,2,4,12',
            'detalles' => 'required|array',
            'fecha_inicio' => ['required_with:fecha_vencimiento', 'max:255', function ($attribute, $value, $fail) use ($request) {
                if ($request->filled('fecha_vencimiento')) {
                    $fechaInicio = Carbon::parse($value);
                    $fechaVencimiento = Carbon::parse($request->fecha_vencimiento);
                    if ($fechaInicio->diffInMonths($fechaVencimiento) != 12) {
                        $fail('La fecha de vencimiento debe ser exactamente un año después de la fecha de inicio.');
                    }
                }
            }],
            'fecha_vencimiento' => 'required_with:fecha_inicio|max:255',
            'cliente_id' => 'required|exists:clientes,id',
            'aseguradora_id' => 'required|exists:aseguradoras,id',
            'tipo_poliza_id' => 'required|exists:tipo_polizas,id'
        ];

        if ($polizaId) {
            $rules = array_map(function ($rule) {
                if (is_array($rule)) {
                    array_unshift($rule, 'sometimes');
                } else {
                    $rule = 'sometimes|' . $rule;
                }
                return $rule;
            }, $rules);
        }

        $request->validate($rules);
    }

    private function createPoliza(Request $request)
    {
        $polizaData = $request->all();
        $polizaData['detalles'] = json_encode($request->detalles);
        return Poliza::create($polizaData);
    }

    private function createVigenciaPoliza(Poliza $poliza, Request $request)
    {
        return $poliza->vigencias()->create([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);
    }

    private function updatePoliza(Poliza $poliza, Request $request)
    {
        if ($request->has('detalles')) {
            $request->merge(['detalles' => json_encode($request->detalles)]);
        }

        $poliza->update($request->all());
    }

    private function updateVigenciaPoliza(Poliza $poliza, Request $request)
    {
        $latestVigencia = $poliza->vigencias()
            ->where('fecha_vencimiento', '>', Carbon::now())
            ->orderBy('fecha_vencimiento', 'desc')
            ->first();

        if ($latestVigencia) {
            $latestVigencia->update([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_vencimiento' => $request->fecha_vencimiento,
            ]);
        } else {
            $this->createVigenciaPoliza($poliza, $request);
        }
    }
}
