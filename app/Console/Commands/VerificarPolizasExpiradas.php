<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poliza;
use Carbon\Carbon;
use App\Notifications\PolizaExpiradaNotification;

class VerificarPolizasExpiradas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'polizas:verificar-expiradas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y actualiza las pólizas expiradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');
        $estadoExpirada = config('constants.polizaEstados.Expirada');

        $polizas = Poliza::with(['vigencias' => function ($query) {
            $query->orderBy('fecha_vencimiento', 'desc');
        }, 'cliente'])->get();

        foreach ($polizas as $poliza) {
            $latestVigencia = $poliza->vigencias->first();

            if ($latestVigencia && Carbon::parse($latestVigencia->fecha_vencimiento)->lt($today) && $poliza->estado !== $estadoExpirada) {
                $poliza->estado = $estadoExpirada;
                $poliza->save();

                $cliente = $poliza->cliente;

                if ($cliente) {
                    $freshPoliza = Poliza::with(['cliente', 'aseguradora', 'tipoPoliza', 'vigencias', 'vigencias.pagos'])->find($poliza->id);
                    $cliente->notify(new PolizaExpiradaNotification($freshPoliza));
                }
            }
        }

        $this->info('Pólizas expiradas verificadas y actualizadas');
    }
}
