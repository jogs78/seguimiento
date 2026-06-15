<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coordinador extends Model
{
    use HasFactory;

    protected $table = "coordinadores"; 

    public function carrera()
    {
        return $this->hasMany(Carrera::class);
        //return $this->belongsTo(Carrera::class);
    }
    public function periodos()
    {
        return $this->hasMany(Periodo::class);
    }

   
    //cambiado por lo de la relacion hasMany entre coordinador y carrera
    public function proyectos($periodo_id)
    {
        $resultados = DB::table('coordinadores')
            ->join('carreras', 'carreras.coordinador_id', '=', 'coordinadores.id')
            ->join('estudiantes', 'carreras.id', '=', 'estudiantes.carrera_id')
            ->join('proyectos', 'proyectos.id', '=', 'estudiantes.proyecto_id')
            ->where('coordinadores.id', '=', $this->id)
            ->where('proyectos.periodo_id', '=', $periodo_id)
            ->select('proyectos.*')
            ->groupBy('proyectos.id')
            ->get();

        return Proyecto::hydrate($resultados->toArray());
    }
     
    /**
     * Verificar si este coordinador es el Jefe de División
     */
    public function esJefeDivision()
    {
        //  Por correo específico
        $email = $this->correo_electronico ?? $this->user->email ?? null;
        
        $jefesEmails = [
            'maria.cf@tuxtla.tecnm.mx',
            'francisco.rm@tuxtla.tecnm.mx',
            'nestor.mn@tuxtla.tecnm.mx',
        ];
        
        if ($email && in_array($email, $jefesEmails)) {
            return true;
        }
        
        
        return false;
    }
}
