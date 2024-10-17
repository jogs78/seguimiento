<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory;

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function primeros(){
        return $this->hasMany(Primero::class);
    }

    public function segundos(){
        return $this->hasMany(Segundo::class);
    }

    public function ultimos(){
        return $this->hasMany(Ultimo::class);
    }
}
