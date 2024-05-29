<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagos;
use App\Models\Poliza;
use Carbon\Carbon;
use App\Notifications\PolizaVencidaNotification;

class VerificarPagos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagos:verificar-pagos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar estado de pago si esta en mora';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');
        $pagoVencido = config('constants.pagoEstados.Vencido');
        $polizaVencida = config('constants.polizaEstados.Vencida');

        $pagos = Pagos::where('fecha_vencimiento', '<', $today)
            ->where('estado', '!=', $pagoVencido)
            ->get();

        $notifiedPolizas = [];

        foreach ($pagos as $pago) {
            $pago->estado = $pagoVencido;
            $pago->save();

            $polizaId = $pago->vigencia->poliza_id;
            if (!in_array($polizaId, $notifiedPolizas)) {
                $notifiedPolizas[] = $polizaId;

                // ToDO: Fix issue where email fails due to email not being in sandbox
                // $cliente = $pago->vigencia->poliza->cliente;
                // $poliza = $pago->vigencia->poliza;

                // if ($cliente && $poliza) {
                //     $cliente->notify(new PolizaVencidaNotification($poliza));
                // }
            }
        }

        Poliza::whereIn('id', $notifiedPolizas)->update(['estado' => $polizaVencida]);

        $this->info('Estados de pagos y polizas actualizados');
    }
}
