<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cliente extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['nombre', 'dui', 'nit', 'email', 'telefono', 'direccion'];

    public function documentos()
    {
        return $this->hasMany(DocumentosClientes::class, 'cliente_id');
    }

    public function polizas()
    {
        return $this->hasMany(Poliza::class, 'cliente_id');
    }
}
