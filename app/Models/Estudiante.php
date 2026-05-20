<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    
    protected $fillable = ["nombre","apellido_paterno","apellido_materno","correo_electronico","numero_de_control",
                            "proyecto_id","telefono","carrera_id","direccion","institucion_seguridad_social",
                            "numero_de_seguridad_social"
                        ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Estudiante.php
    public function tieneProyecto()
    {
        return \App\Models\Proyecto::whereHas('estudiantes', function ($q) {
            $q->where('id', $this->id);
        })->exists();
    }
    
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }



    public function carrera()
    {
        //Estudiante tiene carrera_id
        return $this->belongsTo(Carrera::class);
    }

    public function parciales(){
        //Parcial tiene estudiante_id
        return $this->hasMany(Parcial::class);
    }

    public function parcial($cual){
        //Parcial tiene estudiante_id
        return $this->hasOne(Parcial::class)->where('consecutivo', $cual);
    }

    public function primer(){
        //Parcial tiene estudiante_id
        return $this->hasOne(Parcial::class)->where('consecutivo', 'primer');
    }

    public function segundo(){
        //Parcial tiene estudiante_id
        return $this->hasOne(Parcial::class)->where('consecutivo', 'segundo');
    }

    public function ultimo(){
        //Parcial tiene estudiante_id
        return $this->hasOne(Ultimo::class);
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoEstudiante::class);
    }
    
}
