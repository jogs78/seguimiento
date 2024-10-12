<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ["nombre", "giro", "rfc","direccion", "telefono", "correo","titular", "puesto_titular", 
    "asesor_externo", "puesto_asesor","informacion"];   

    public function proyectos(){
        return $this->hasMany(Proyecto::class);
    }
}
