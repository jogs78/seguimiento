<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsesorCarrera extends Model
{
    use HasFactory;

    protected $table = "asesor_carrera";

    protected $fillable = [
        'asesor_id',
        'carrera_id'
    ];
}