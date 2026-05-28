<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $nueva = new Configuracion();
        $nueva->variable = "jefe_division";
        $nueva->valor = "DR. FCO.ALEXANDER RINCON MOLINA";
        $nueva->carrera_id = null;
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "tecnologico";
        $nueva->valor = "Tecnológico de Tuxtla Gutiérrez";
        $nueva->carrera_id = null;
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "año";
        $nueva->valor = "2026";
        $nueva->carrera_id = null;
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "periodo_id";
        $nueva->valor = "11";
        $nueva->carrera_id = null;
        $nueva->save();

         // CONFIGURACIÓN POR CARRERA
        // interno   = no por defecto para todas las carreras

        $carreras = Carrera::all();

        foreach ($carreras as $carrera) {

            $nueva = new Configuracion();
            $nueva->variable = "interno";
            $nueva->valor = "no";
            $nueva->carrera_id = $carrera->id;
            $nueva->save();
        }

        //peticion fuera de tiempo
        /*
        foreach ($carreras as $carrera) {

            $nueva = new Configuracion();
            $nueva->variable = "fuera_de_tiempo";
            $nueva->valor = "no";
            $nueva->carrera_id = $carrera->id;
            $nueva->save();
        }*/
    }
}
