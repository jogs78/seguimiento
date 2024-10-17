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

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
