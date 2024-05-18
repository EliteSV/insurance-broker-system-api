<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    use HasFactory;

    protected $fillable = ['vigencia_poliza_id', 'cantidad', 'fecha_vencimiento', 'fecha_pagado', 'comprobante', 'estado', 'cuota'];

    public function vigencia()
    {
        return $this->belongsTo(VigenciaPolizas::class, 'vigencia_poliza_id');
    }
}
