<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UltimoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ultimo = new Ultimo();
        $ultimo->portada = 2;
        $ultimo->agradecimientos = 2;
        $ultimo->resumen = 2;
        $ultimo->indice = 2;
        $ultimo->introduccion = 2;
        $ultimo->problemas = 5;
        $ultimo->objetivos = 2;
        $ultimo->justificacion = 3;
        $ultimo->marco_teorico = 10;
        $ultimo->procedimiento = 5;
        $ultimo->resultados = 45;
        $ultimo->conclusiones = 15;
        $ultimo->competencias = 3;
        $ultimo->fuentes = 2;
        //$ultimo->ruta = "C:\\Users\\omner\\Desktop";
        $ultimo->save(); 

        $ultimo = new Ultimo();
        $ultimo->portada = 2;
        $ultimo->agradecimientos = 1;
        $ultimo->resumen = 2;
        $ultimo->indice = 2;
        $ultimo->introduccion = 1;
        $ultimo->problemas = 4;
        $ultimo->objetivos = 2;
        $ultimo->justificacion = 3;
        $ultimo->marco_teorico = 9;
        $ultimo->procedimiento = 5;
        $ultimo->resultados = 40;
        $ultimo->conclusiones = 15;
        $ultimo->competencias = 3;
        $ultimo->fuentes = 2;
        //$ultimo->ruta = "C:\\Users\\jorge\\Pictures";
        $ultimo->save(); 
    }

}
