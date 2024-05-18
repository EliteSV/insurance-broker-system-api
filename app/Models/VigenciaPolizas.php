<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VigenciaPolizas extends Model
{
    use HasFactory;

    protected $table = 'vigencia_polizas';

    protected $fillable = ['fecha_inicio', 'fecha_vencimiento', 'poliza_id'];

    public function poliza()
    {
        return $this->belongsTo(Poliza::class, 'poliza_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pagos::class, 'vigencia_poliza_id');
    }
}
