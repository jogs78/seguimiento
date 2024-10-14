<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proyecto;

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
        $proyecto->justificacion = "El desarrollo de un sistema para el control del proceso administrativo de seguimiento a las 
residencias en el departamento de ingenierías en el Instituto Tecnológico de Tuxtla Gutiérrez";
        $proyecto->asesor_id = 1;
        $proyecto->empresa_id = 1;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->nombre = "API para la automatización de proceso de titulación del Tecnm / Tuxtla Gutiérrez";
        $proyecto->objetivo_general = "Desarrollar un API para la automatización de proceso de titulación usando el framework Laravel.";
        $proyecto->lugar = "Tecnológico Nacional de México Campus Tuxtla Gutiérrez";
        $proyecto->informacion = "contacto@ittg.edu.mx";
        $proyecto->justificacion = "La implementación de una API para la automatización del proceso de titulación tiene como objetivo 
principal optimizar y agilizar los trámites necesarios";
        $proyecto->asesor_id = 2;
        $proyecto->empresa_id = 1;
        $proyecto->periodo_id = 1;

        $proyecto->save(); 
    }
}
