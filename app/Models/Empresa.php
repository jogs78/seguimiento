<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = ["nombre", "giro", "rfc","direccion","numero","codigo_postal","ciudad","estado", "telefono", "correo","titular", "puesto_titular","informacion"];   

    public function proyectos(){
        return $this->hasMany(Proyecto::class);
    }
}
