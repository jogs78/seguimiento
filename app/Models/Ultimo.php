<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ultimo extends Model
{
    use HasFactory;
    protected $fillable=[
                    'estudiante_id','portada','agradecimientos','resumen','indice','introduccion', 
                    'problemas','objetivos', 'justificacion', 'marco_teorico', 'procedimiento',
                    'resultados', 'conclusiones', 'competencias', 'fuentes'
                      ];
    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
