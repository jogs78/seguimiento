<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    use HasFactory;
    protected $table = "cronogramas";
    protected $connection = 'mysql';
    protected $fillable = ["id", "actividad_id", "orden", "semana_inicio", "semana_fin"];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }
}
