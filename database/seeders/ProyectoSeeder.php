<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProyectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proyecto = new Proyecto();
        $proyecto->nombre = "Sistematización del seguimiento de los procesos de residencia profesional en el departamento de ingenierías";
        $proyecto->objetivo_general = "Desarrollar un sistema que automatice la supervisión de residencia profesional en el departamento de ingenierías";
        $proyecto->lugar = "Tecnológico Nacional de México Campus Tuxtla Gutiérrez";
        $proyecto->informacion = "contacto@ittg.edu.mx";
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->nombre = "API para la automatización de proceso de titulación del Tecnm / Tuxtla Gutiérrez";
        $proyecto->objetivo_general = "Desarrollar un API para la automatización de proceso de titulación usando el framework Laravel.";
        $proyecto->lugar = "Tecnológico Nacional de México Campus Tuxtla Gutiérrez";
        $proyecto->informacion = "contacto@ittg.edu.mx";
        $proyecto->save(); 
    }
}
