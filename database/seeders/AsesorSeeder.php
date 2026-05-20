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
        //$asesor->id = 1;
        $asesor->nombre = "Jorge Octavio";
        $asesor->apellido_paterno = "Guzman";
        $asesor->apellido_materno = "Sanchez";
        $asesor->correo_electronico = "jorge.gs1@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En Sistemas Computacionales";
        $asesor->carrera = "Sistemas";
        $asesor->numero_cedula = "54630964";
        $asesor->save(); 

        $asesor = new Asesor();
        //$asesor->id = 2;
        $asesor->nombre = "Jesus Carlos";
        $asesor->apellido_paterno = "Sanchez";
        $asesor->apellido_materno = "Guzman";
        $asesor->correo_electronico = "JesusCSGuzman@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria En sistemas computacionales";
        $asesor->carrera = "Sistemas";
        $asesor->numero_cedula = "90783614";
        $asesor->save(); 

        $asesor = new Asesor();
        //$asesor->id = 3;
        $asesor->nombre = "Nestor Antonio";
        $asesor->apellido_paterno = "Morales";
        $asesor->apellido_materno = "Navarro";
        $asesor->correo_electronico = "NAntonioMN@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria Industrial";
        $asesor->carrera = "Industrial";
        $asesor->numero_cedula = "85764275";
        $asesor->save(); 

        $asesor = new Asesor();
        //$asesor->id = 4;
        $asesor->nombre = "Walter";
        $asesor->apellido_paterno = "Torres";
        $asesor->apellido_materno = "Robledo";
        $asesor->correo_electronico = "MexicoLed@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria en Electronica";
        $asesor->carrera = "Electronica";
        $asesor->numero_cedula = "76826594";
        $asesor->save(); 

        $asesor = new Asesor();
        //$asesor->id = 5;
        $asesor->nombre = "Hector";
        $asesor->apellido_paterno = "Guerra";
        $asesor->apellido_materno = "Crespo";
        $asesor->correo_electronico = "HGuerraC@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria en Sistemas Computacionales";
        $asesor->carrera = "Sistemas";
        $asesor->numero_cedula = "12345678";
        $asesor->save(); 

        $asesor = new Asesor();
        //$asesor->id = 6;
        $asesor->nombre = "Margarita";
        $asesor->apellido_paterno = "Roblero";
        $asesor->apellido_materno = "Paredes";
        $asesor->correo_electronico = "MrobleroP@tuxtla.tecnm.mx";
        $asesor->profesion = "Eletromecanica";
        $asesor->carrera = "Electronica";
        $asesor->numero_cedula = "76474156";
        $asesor->save();

        $asesor = new Asesor();
        //$asesor->id = 7;
        $asesor->nombre = "Ariana";
        $asesor->apellido_paterno = "Esquinca";
        $asesor->apellido_materno = "Torres";
        $asesor->correo_electronico = "ArianaET@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria Indsutrial";
        $asesor->carrera = "Industrial";
        $asesor->numero_cedula = "76537156";
        $asesor->save();

        $asesor = new Asesor();
        //$asesor->id = 8;
        $asesor->nombre = "Uriel";
        $asesor->apellido_paterno = "Fernadez";
        $asesor->apellido_materno = "Mendez";
        $asesor->correo_electronico = "Uriel@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria Mecanica";
        $asesor->carrera = "Mecanica";
        $asesor->numero_cedula = "75643421";
        $asesor->save();

        $asesor = new Asesor();
        //$asesor->id = 9;
        $asesor->nombre = "Jeremias";
        $asesor->apellido_paterno = "Camacho";
        $asesor->apellido_materno = "Faundez";
        $asesor->correo_electronico = "jeremiasCF@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria en Electrica";
        $asesor->carrera = "Electrica";
        $asesor->numero_cedula = "64578221";
        $asesor->save();

        $asesor = new Asesor();
        //$asesor->id = 10;
        $asesor->nombre = "Ruth";
        $asesor->apellido_paterno = "Santiago";
        $asesor->apellido_materno = "Choc";
        $asesor->correo_electronico = "RuthSC@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria Mecanica";
        $asesor->carrera = "Macanica";
        $asesor->numero_cedula = "09897682";
        $asesor->save();

        $asesor = new Asesor();
        //$asesor->id = 11;
        $asesor->nombre = "Martha";
        $asesor->apellido_paterno = "Narvez";
        $asesor->apellido_materno = "Guzman";
        $asesor->correo_electronico = "MArthaNG@tuxtla.tecnm.mx";
        $asesor->profesion = "Ingenieria Industrial";
        $asesor->carrera = "Industrial";
        $asesor->numero_cedula = "86737682";
        $asesor->save();
    }

}
