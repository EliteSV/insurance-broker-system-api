<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagos;
use App\Models\Poliza;
use Carbon\Carbon;

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

        $pagos = Pagos::where('fecha_vencimiento', '<', $today)
            ->where('estado', '!=', 'Vencido')
            ->get();

        foreach ($pagos as $pago) {
            $pago->estado = 'Vencido';
            $pago->save();
            $polizaIds[] = $pago->vigencia->poliza_id;
        }

        $uniquePolizaIds = array_unique($polizaIds);

        Poliza::whereIn('id', $uniquePolizaIds)->update(['estado' => 'Vencido']);

        $this->info('Estados de pagos y polizas actualizados');
    }
}
