<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'extensiones_permitidas',
        'tamano_maximo_mb',
        'obligatorio',
        'permite_url',
        'activo'
    ];

    public function documentos()
    {
        return $this->hasMany(DocumentoEstudiante::class);
    }
}
