<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;
    protected $fillable = ["nombre", "objetivo_general", "lugar", "informacion", "justificacion"];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class);
    }

    public function seguimiento()
    {
        return $this->belongsTo(Seguimientos::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function especificos(){
        return $this->hasMany(Especifico::class);
    }

    public function seguimientos(){
        return $this->hasMany(Seguimiento::class);
    }
}
