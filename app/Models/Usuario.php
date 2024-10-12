<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;
    public function usa()//: MorphToMany
    {
//        return $this->morphOne()  morphToMany(Medio::class, 'usa','usables');
        return $this->morphTo();

    }

}
