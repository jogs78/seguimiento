<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = ["nombre","apellido","correo_electronico","nombre_proyecto",
                            "nombre_asesor","apellido_asesor"];
}
