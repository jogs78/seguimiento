<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;
    protected $table = "configuraciones";
    protected $fillable = ["id", "variable", "valor", "tipo", "tabla", "campo", "carrera_id"];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
