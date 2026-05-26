<?php

namespace Database\Seeders;
use App\Models\Cronograma;
use App\Models\Actividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CronogramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD 1
        |--------------------------------------------------------------------------
        */

        $nuevo = new Cronograma();
        $nuevo->actividad_id = 1;
        $nuevo->semana_inicio = 1;
        $nuevo->semana_fin = 2;
        $nuevo->orden = 1;
        $nuevo->save();



        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD 2
        |--------------------------------------------------------------------------
        */

        $nuevo = new Cronograma();
        $nuevo->actividad_id = 2;
        $nuevo->semana_inicio = 3;
        $nuevo->semana_fin = 4;
        $nuevo->orden = 2;
        $nuevo->save();



        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD 3
        |--------------------------------------------------------------------------
        */

        $nuevo = new Cronograma();
        $nuevo->actividad_id = 3;
        $nuevo->semana_inicio = 5;
        $nuevo->semana_fin = 10;
        $nuevo->orden = 3;
        $nuevo->save();



        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD 4
        |--------------------------------------------------------------------------
        */

        $nuevo = new Cronograma();
        $nuevo->actividad_id = 4;
        $nuevo->semana_inicio = 3;
        $nuevo->semana_fin = 3;
        $nuevo->orden = 4;
        $nuevo->save();



        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD 5
        |--------------------------------------------------------------------------
        */

        $nuevo = new Cronograma();
        $nuevo->actividad_id = 5;
        $nuevo->semana_inicio = 1;
        $nuevo->semana_fin = 2;
        $nuevo->orden = 5;
        $nuevo->save();



        /*
        |--------------------------------------------------------------------------
        | REUTILIZACIÓN DE ACTIVIDAD
        |--------------------------------------------------------------------------
        | Ejemplo: la actividad "Análisis"
        | también se realiza nuevamente
        | en semanas posteriores
        |--------------------------------------------------------------------------
        */

        $nuevo = new Cronograma();
        $nuevo->actividad_id = 1;
        $nuevo->semana_inicio = 8;
        $nuevo->semana_fin = 9;
        $nuevo->orden = 6;
        $nuevo->save();
    }
}
