<?php

namespace Database\Seeders;
use App\Models\Actividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $nueva = new Actividad();
        $nueva->orden = "1";
        $nueva->nombre = "analisis";
        $nueva->semanas = 2;
        $nueva->descripcion = "Identificar y contactar a los responsables.";
        $nueva->save();
        $nueva = new Actividad();
        $nueva->orden = "3";
        $nueva->nombre = "diseño";
        $nueva->semanas = 2;
        $nueva->descripcion = "Maquetar y graficar";
        $nueva->save();
        $nueva = new Actividad();
        $nueva->orden = "6";
        $nueva->nombre = "implementacion";
        $nueva->semanas = 2;
        $nueva->descripcion = "Desarollar la vista del log in";
        $nueva->save();

        /*
         $actividad = new Actividad();
        $actividad->orden = "3";
        $actividad->nombre = "Entrevistas";
        $actividad->semanas = "1";
        $actividad->descripcion = "Identificar y contactar a los responsables.";
        $actividad->save(); 

         $actividad = new Actividad();
        $actividad->orden = "1";
        $actividad->nombre = "Definir requisitos";
        $actividad->semanas = "2";
        $actividad->descripcion = "Analizar y registrar los requisitos funcionales y no funcionales del sistema.";
        $actividad->save(); 
        */


    }
}
