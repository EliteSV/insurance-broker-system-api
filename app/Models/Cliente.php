<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'dui', 'nit', 'email', 'telefono'];

    public function documentos()
    {
        return $this->hasMany(DocumentosClientes::class, 'cliente_id');
    }
}

