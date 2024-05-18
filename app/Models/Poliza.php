<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliza extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'estado', 'monto', 'cuotas', 'detalles', 'cliente_id', 'aseguradora_id', 'tipo_poliza_id'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class, 'aseguradora_id');
    }

    public function tipoPoliza()
    {
        return $this->belongsTo(TipoPoliza::class, 'tipo_poliza_id');
    }

    public function vigencias()
    {
        return $this->hasMany(VigenciaPolizas::class, 'poliza_id');
    }

    public function calculateGanancia()
    {
        $renewalCount = $this->vigencias()->count();
        return round($this->monto * 0.10 * $renewalCount, 2);
    }
}
