<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asesor;

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        $asesor = new Asesor();
        $asesor->nombre = "Jorge Octavio";
        $asesor->apellido_paterno = "Guzman";
        $asesor->apellido_materno = "Sanchez";
        $asesor->correo_electronico = "jorge.gs1@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En Sistemas Computacionales";
        $asesor->numero_cedula = "54630964";
        $asesor->save(); 

        $asesor = new Asesor();
        $asesor->nombre = "Fernando";
        $asesor->apellido_paterno = "Mendoza";
        $asesor->apellido_materno = "Mora";
        $asesor->correo_electronico = "fernando.mm2@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En Electronica";
        $asesor->numero_cedula = "94405831";
        $asesor->save(); 
    }

}
