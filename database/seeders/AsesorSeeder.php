<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        $asesor = new Asesor();
        $asesor->nombre_asesor = "Jorge Octavio";
        $asesor->apellido_paterno_asesor = "Guzman";
        $asesor->apellido_materno_asesor = "Sanchez";
        $asesor->correo_electronico = "jorge.gs1@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En Sistemas Computacionales";
        $asesor->numero_cedula = "54630964";
        $asesor->save(); 

        $asesor = new Asesor();
        $asesor->nombre_asesor = "Fernando";
        $asesor->apellido_paterno_asesor = "Mendoza";
        $asesor->apellido_materno_asesor = "Mora";
        $asesor->correo_electronico = "fernando.mm2@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En Electronica";
        $asesor->numero_cedula = "94405831";
        $asesor->save(); 
    }

}
