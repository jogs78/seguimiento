<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory;

    
    public function parciales()//model
    {
        return $this->morphMany(Parcial::class, 'desglose');
    }
    public function parcial( $cual = 1)//model
    {
        return $this->morphMany(Parcial::class, 'desglose')->where('orden',$cual);
    }


    public function ultimos(){
        return $this->hasMany(Ultimo::class);
    }
    
}
