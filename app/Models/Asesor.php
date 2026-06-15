<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;
    protected $table = "asesores";
    protected $fillable = ["nombre", "apellido_paterno", "apellido_materno","correo_electronico", "profesion", "carrera","numero_cedula"];

    /*
    
       public function proyectos($periodo_id = null)
    {
        $query = $this->hasMany(Proyecto::class);
        
        if ($periodo_id) {
            $query->where('periodo_id', $periodo_id);
        }
        
        return $query;
    } 
    */
    public function proyectos($periodo_id)
    { 
        return $this->hasMany(Proyecto::class)->where('periodo_id', $periodo_id);
    }
    //nuevo por lo de la tabla pivote
    public function carreras(){
        return $this->belongsToMany(Carrera::class, 'asesor_carrera', 'asesor_id', 'carrera_id');
    }
}
