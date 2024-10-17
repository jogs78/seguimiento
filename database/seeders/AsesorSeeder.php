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
        $asesor->id = 1;
        $asesor->nombre = "Jorge Octavio";
        $asesor->apellido_paterno = "Guzman";
        $asesor->apellido_materno = "Sanchez";
        $asesor->correo_electronico = "jorge.gs1@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En Sistemas Computacionales";
        $asesor->numero_cedula = "54630964";
        $asesor->save(); 

        $asesor = new Asesor();
        $asesor->id = 2;
        $asesor->nombre = "Jesus Carlos";
        $asesor->apellido_paterno = "Sanchez";
        $asesor->apellido_materno = "Guzman";
        $asesor->correo_electronico = "JesusCSGuzman@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En sistemas computacionales";
        $asesor->numero_cedula = "90783614";
        $asesor->save(); 

        $asesor = new Asesor();
        $asesor->id = 3;
        $asesor->nombre = "Nestor Antonio";
        $asesor->apellido_paterno = "Morales";
        $asesor->apellido_materno = "Navarro";
        $asesor->correo_electronico = "NAntonioMN@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria Industrial";
        $asesor->numero_cedula = "85764275";
        $asesor->save(); 

        $asesor = new Asesor();
        $asesor->id = 4;
        $asesor->nombre = "Walter";
        $asesor->apellido_paterno = "Torres";
        $asesor->apellido_materno = "Robledo";
        $asesor->correo_electronico = "MexicoLed@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria en Electronica";
        $asesor->numero_cedula = "76826594";
        $asesor->save(); 

        $asesor = new Asesor();
        $asesor->id = 5;
        $asesor->nombre = "Hector";
        $asesor->apellido_paterno = "Guerra";
        $asesor->apellido_materno = "Crespo";
        $asesor->correo_electronico = "HGuerraC@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria en Sistemas Computacionales";
        $asesor->numero_cedula = "12345678";
        $asesor->save(); 
    }

}
