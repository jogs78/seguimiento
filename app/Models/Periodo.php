<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = ["nombre", "fecha_inicio", "fecha_final", "fecha_inicio_1er_reporte", "fecha_final_1er_reporte",
                           "fecha_inicio_2do_reporte", "fecha_final_2do_reporte", "fecha_inicio_reporte_final", "fecha_final_reporte_final"
                        ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function seguimientos(){
        return $this->hasMany(Seguimiento::class);
    }



}
