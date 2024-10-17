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
        $proyecto->id = 1;
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
        $proyecto->id = 2;
        $proyecto->nombre = "Optimizacion de la infrestura de servicios gubernamentales en chiapas a través de microservicios";
        $proyecto->objetivo_general = "Desarrollo de un app movil de micoservicios para agilizar tramites en el esta de chiapas";
        $proyecto->lugar = "Instituto de ciencia, tecnologia e innovacion del estado de chiapas";
        $proyecto->informacion = "InstitutoDeCiencia@gmail.com";
        $proyecto->justificacion = "Agilizacion de los tramites del estado de chiapas";
        $proyecto->asesor_id = 2;
        $proyecto->empresa_id = 2;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 3;
        $proyecto->nombre = "Plataforma integral de gestion y mejora de procesos para el area de transmision en CFE, modulo de monitoreo";
        $proyecto->objetivo_general = "Desarrollo de un modulo para el monitoreo de la transmision ";
        $proyecto->lugar = "CFE Tranmision";
        $proyecto->informacion = "CfeTransmision@gmail.com";
        $proyecto->justificacion = "Mejorar los procesos de tranmision, atraves de un modulo de monitoreo";
        $proyecto->asesor_id = 1;
        $proyecto->empresa_id = 3;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 4;
        $proyecto->nombre = "Sistema integral de procesos administrativos de la secretaria de infraestructura, comunicaciones y transporte (Modulo 1: Autentificacion, Modulo 2: Registro)";
        $proyecto->objetivo_general = "Desarrollo de 2 modulos de seguridad para la autentificacion y registro de empleados";
        $proyecto->lugar = "Secretaria de Infraestructura, Comunicaciones y transporte";
        $proyecto->informacion = "SecDeInfraestrutura@gmail.com";
        $proyecto->justificacion = "Crear modulos para la seguridad de los empleados y la proyecto";
        $proyecto->asesor_id = 3;
        $proyecto->empresa_id = 4;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 5;
        $proyecto->nombre = "Diseño y construccion de kit educativo (Raccoon CanSat Cosmo)";
        $proyecto->objetivo_general = "Creacion de una herramienta educativa para expirimentacion satelital";
        $proyecto->lugar = "COSMOS71";
        $proyecto->informacion = "Cosmos71@gmail.com";
        $proyecto->justificacion = "Dajar al alcanze de los estudiantes un herramienta para experimentacion satelital";
        $proyecto->asesor_id = 4;
        $proyecto->empresa_id = 5;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 6;
        $proyecto->nombre = "Sistema de cursos de capacitacion para trabajadores de CFE";
        $proyecto->objetivo_general = "Desarrollo de una plataforma de capacitacion";
        $proyecto->lugar = "Comision Federal de Electricidad";
        $proyecto->informacion = "CFE@hotmail.com";
        $proyecto->justificacion = "Dejar un sitio de facil acceso 24/7 a capacitacion para los trabajadores";
        $proyecto->asesor_id = 2;
        $proyecto->empresa_id = 6;
        $proyecto->periodo_id = 2;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 7;
        $proyecto->nombre = "Rehabilitacion de Tecnologias";
        $proyecto->objetivo_general = "Evaluar, Reparar y dar mantenimiento a el equipo de computacion de la proyecto";
        $proyecto->lugar = "Instituto De Elecciones y participacion Ciudadana (IEPC)";
        $proyecto->informacion = "IEPC@hotmail.com";
        $proyecto->justificacion = "Existe equipo computacional nuevo y antiguo en un mismo sitio por lo que se tendra que evaluar que equipos son utiles aun";
        $proyecto->asesor_id = 2;
        $proyecto->empresa_id = 7;
        $proyecto->periodo_id = 2;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 8;
        $proyecto->nombre = "Exportacion de Listas de acuerdos";
        $proyecto->objetivo_general = "Exportar los acuerdos nombrados en las listas";
        $proyecto->lugar = "Consejo de la Judicatura del poder Judicial del estado";
        $proyecto->informacion = "ConsejoJudicatura@hotmail.com";
        $proyecto->justificacion = "Tener los acuerdos alcanze de todos los trabajadores y empleados";
        $proyecto->asesor_id = 1;
        $proyecto->empresa_id = 8;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 9;
        $proyecto->nombre = "Diseño e implementacion de un MIPS para el FPGA ";
        $proyecto->objetivo_general = "Uso de MIPS en el Proyecto (Dielect)";
        $proyecto->lugar = "Tecnológico Nacional de México Campus Tuxtla Gutiérrez";
        $proyecto->informacion = "contacto@ittg.edu.mx";
        $proyecto->justificacion = "Optimizacion del proyecto (Dielect) mediante el uso del FPGA";
        $proyecto->asesor_id = 4;
        $proyecto->empresa_id = 1;
        $proyecto->periodo_id = 2;
        $proyecto->save(); 

        $proyecto = new Proyecto();
        $proyecto->id = 10;
        $proyecto->nombre = "Generacion de ruta de aprendizaje basado en modelo de planifiacion de inteligencia artifiacial";
        $proyecto->objetivo_general = "Diseño de un modelo de aprendizaje de inteligencia artificial";
        $proyecto->lugar = "Tecnológico Nacional de México Campus Tuxtla Gutiérrez";
        $proyecto->informacion = "contacto@ittg.edu.mx";
        $proyecto->justificacion = "Facilitar el aprendizaje de inteligencia artificial";
        $proyecto->asesor_id = 5;
        $proyecto->empresa_id = 1;
        $proyecto->periodo_id = 2;
        $proyecto->save();

        $proyecto = new Proyecto();
        $proyecto->id = 11;
        $proyecto->nombre = "API para la automatización de proceso de titulación del Tecnm / Tuxtla Gutiérrez";
        $proyecto->objetivo_general = "Desarrollar un API para la automatización de proceso de titulación usando el framework Laravel.";
        $proyecto->lugar = "Tecnológico Nacional de México Campus Tuxtla Gutiérrez";
        $proyecto->informacion = "contacto@ittg.edu.mx";
        $proyecto->justificacion = "La implementación de una API para la automatización del proceso de titulación tiene como objetivo 
principal optimizar y agilizar los trámites necesarios";
        $proyecto->asesor_id = 1;
        $proyecto->empresa_id = 1;
        $proyecto->periodo_id = 1;
        $proyecto->save(); 


    }
}
