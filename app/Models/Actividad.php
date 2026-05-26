<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    protected $table = "actividades";
    protected $connection = 'mysql';
    protected $fillable = ["id", "nombre","descripcion", "proyecto_id"];


    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function cronogramas()
    {
        return $this->hasMany(Cronograma::class)->orderBy('orden');
    }
}
