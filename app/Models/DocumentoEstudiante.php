<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoEstudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id',
        'tipo_documento_id',
        'ruta_archivo',
        'nombre_original',
        'mime_type',
        'tamano',
        'url'
    ];
    
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }
}
