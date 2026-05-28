<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;
    protected $fillable = ['num_registro', 'nombre', 'objetivo_general', 'lugar', 'informacion', 'justificacion', 'asesor_id', 'externo_id', 'empresa_id', 'periodo_id','fuera_de_tiempo'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class)->withDefault([
            'nombre' => 'Empresa No Definida',
        ]);
    }
    public function asesor()
    {
        return $this->belongsTo(Asesor::class)->withDefault(['nombre'=>'No asignado']);
    }

    public function seguimiento()
    {
        return $this->belongsTo(Seguimientos::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
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

    public function externo(){
        //porque tenemos externo_id
        return $this->belongsTo(Externo::class);
    }

    
}
