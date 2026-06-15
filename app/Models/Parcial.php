<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcial extends Model
{
    use HasFactory;
    protected $fillable = [
        'estudiante_id',
        'promedio_parcial', 
        'consecutivo',

        'puntualidad_externo',
        'equipo_externo',
        'iniciativa_externo',
        'mejoras_externo', 
        'objetivos_externo', 
        'orden_externo', 
        'liderazgo_externo', 
        'conocimiento_externo',
        'etico_externo', 
        'promedio_externo',
        'comentarios_externo',



        'puntualidad_interno',
        'conocimiento_interno',
        'equipo_interno',
        'dedicado_interno', 
        'orden_interno', 
        'mejoras_interno', 
        'promedio_interno', 
        'comentarios_interno',



        'ruta' 
    ];
    protected $table = "parciales";
   

    public function seguimiento()
    {
        return $this->morphTo();
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
