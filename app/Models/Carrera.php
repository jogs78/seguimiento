<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = "carreras";
    protected $fillable = ["nombre", "coordinador_id"];

    public function estudiantes(){
        return $this->hasMany(Estudiante::class);
    }

    public function periodo()
    {
        return $this->hasOne(Periodo::class);
    }

    public function coordinador()
    {
        return $this->belongsTo(Coordinador::class);
        //return $this->hasOne(Coordinador::class);
    }

    //nuevo por lo de la tabla pivote
    public function asesores()
    {
        return $this->belongsToMany(Asesor::class, 'asesor_carrera', 'carrera_id', 'asesor_id');
    }

    public function configuraciones()
    {
        return $this->hasMany(Configuracion::class);
    }
}
