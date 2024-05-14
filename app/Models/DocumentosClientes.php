<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosClientes extends Model
{
    use HasFactory;

    protected $table = 'documentos_clientes'; 

    protected $fillable = ['url', 'cliente_id', 'tipo_documento_id'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TiposDocumentos::class, 'tipo_documento_id');
    }
}
