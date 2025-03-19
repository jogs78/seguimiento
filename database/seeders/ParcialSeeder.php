<?php

namespace Database\Seeders;
use App\Models\Parcial;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParcialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $parcial = new Parcial();
        
        $parcial->estudiante_id = 1;
        $parcial->promedio_parcial = 100;
        $parcial->consecutivo = 1;


        $parcial->puntualidad_interno = 10;
        $parcial->conocimiento_interno = 20;
        $parcial->equipo_interno = 15;
        $parcial->dedicado_interno = 20;
        $parcial->orden_interno = 20;
        $parcial->mejoras_interno = 15;
        $parcial->promedio_interno = 15;  
        $parcial->comentarios_interno = "primer interno comentario";   
        $parcial->califico_interno = '2025-03-11 15:30:00';     
   

        $parcial->puntualidad_externo = 5;
        $parcial->equipo_externo = 10;
        $parcial->iniciativa_externo = 5;
        $parcial->mejoras_externo = 10;
        $parcial->objetivos_externo = 15;
        $parcial->orden_externo = 15;
        $parcial->liderazgo_externo = 10;  
        $parcial->conocimiento_externo = 20;
        $parcial->etico_externo = 10;
        $parcial->promedio_externo = 100;
        $parcial->comentarios_externo = "primer externo comentario";
        $parcial->califico_externo = '2025-03-11 14:30:00';  



        $parcial->save(); 
////////////////
/*
        $parcial = new Parcial();
        $parcial->estudiante_id = 1;
        $parcial->promedio_parcial = 100;
        $parcial->consecutivo = 2;
        $parcial->puntualidad_interno = 10;
        $parcial->conocimiento_interno = 20;
        $parcial->equipo_interno = 15;
        $parcial->dedicado_interno = 20;
        $parcial->orden_interno = 20;
        $parcial->mejoras_interno = 15;
        $parcial->promedio_interno = 15;  
        $parcial->comentarios_interno = "segundo interno comentario";        
        $parcial->puntualidad_externo = 5;
        $parcial->equipo_externo = 10;
        $parcial->iniciativa_externo = 5;
        $parcial->mejoras_externo = 10;
        $parcial->objetivos_externo = 15;
        $parcial->orden_externo = 15;
        $parcial->liderazgo_externo = 10;  
        $parcial->conocimiento_externo = 20;
        $parcial->etico_externo = 10;
        $parcial->promedio_externo = 100;
        $parcial->comentarios_externo = "segundo externo comentario";        
        $parcial->save(); 
*/ 
////////////////
        $parcial = new Parcial();
        $parcial->estudiante_id = 2;
        $parcial->promedio_parcial = 100;
        $parcial->consecutivo = 1;
        $parcial->puntualidad_interno = 10;
        $parcial->conocimiento_interno = 20;
        $parcial->equipo_interno = 15;
        $parcial->dedicado_interno = 20;
        $parcial->orden_interno = 20;
        $parcial->mejoras_interno = 15;
        $parcial->promedio_interno = 15;  
        $parcial->comentarios_interno = "segundo interno comentario";        
        $parcial->puntualidad_externo = 5;
        $parcial->equipo_externo = 10;
        $parcial->iniciativa_externo = 5;
        $parcial->mejoras_externo = 10;
        $parcial->objetivos_externo = 15;
        $parcial->orden_externo = 15;
        $parcial->liderazgo_externo = 10;  
        $parcial->conocimiento_externo = 20;
        $parcial->etico_externo = 10;
        $parcial->promedio_externo = 100;
        $parcial->comentarios_externo = "segundo externo comentario";  
        $parcial->califico_externo = '2025-02-11 14:30:00';  
        $parcial->califico_interno = '2025-02-11 15:30:00';       
        $parcial->save(); 

         
////////////////
     }
}
