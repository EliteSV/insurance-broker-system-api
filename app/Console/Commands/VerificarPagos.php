<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pagos;
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
            ->where('estado', '!=', 'En Mora')
            ->get();

        foreach ($pagos as $pago) {
            $pago->estado = 'En Mora';
            $pago->save();
        }

        $this->info('Estados de pagos actualizados');
    }
}
