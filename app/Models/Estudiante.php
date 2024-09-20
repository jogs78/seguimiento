<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    
    protected $fillable = ["nombre","apellido_paterno","apellido_materno","correo_electronico","numero_de_control",
                            "proyecto_id","telefono","carrera_id","direccion","institucion_seguiridad_social","numero_de_seguridad_social"];
}
