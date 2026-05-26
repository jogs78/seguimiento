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
        $nueva->nombre = "analisis";
        $nueva->descripcion = "Identificar y contactar a los responsables.";
        $nueva->proyecto_id = 1;
        $nueva->save();

        $nueva = new Actividad();
        $nueva->nombre = "diseño";
        $nueva->descripcion = "Maquetar y graficar";
        $nueva->proyecto_id = 1;
        $nueva->save();

        $nueva = new Actividad();
        $nueva->nombre = "implementacion";
        $nueva->descripcion = "Desarollar la vista del log in";
        $nueva->proyecto_id = 1;
        $nueva->save();

        $nueva = new Actividad();
        $nueva->nombre = "Entrevistas";
        $nueva->descripcion = "Identificar y contactar a los responsables.";
        $nueva->proyecto_id = 3;
        $nueva->save(); 

        $nueva = new Actividad();
        $nueva->nombre = "Definir requisitos";
        $nueva->descripcion = "Analizar y registrar los requisitos funcionales y no funcionales del sistema.";
        $nueva->proyecto_id = 3;
        $nueva->save(); 


    }
}
