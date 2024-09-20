<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EspecificoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especifico = new Especifico();
        $especifico->orden = "3";
        $especifico->texto = "Investigar a profundidad el proceso de titulación para su entendimiento a detalle realizando entrevistas e investigación documental.";
        $especifico->save(); 

        $especifico = new Especifico();
        $especifico->orden = "1";
        $especifico->texto = "El desarrollo de un sistema para el control del proceso administrativo de seguimiento a las residencias en el departamento de ingenierías en el Instituto Tecnológico de Tuxtla Gutiérrez brinda una reducción de carga de trabajo al personal administrativo, dado que son muchos los alumnos lo que solicitan.";
        $especifico->save(); 
    }

}
