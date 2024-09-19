<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void 
    {
        $periodo = new Periodo();
        $periodo->nombre = "Agosto - Diciembre 2024";
        $periodo->fecha_inicio = "2024-08-26";
        $periodo->fecha_final = "2024-12-15";
        $periodo->fecha_inicio_1er_reporte = "2024-08-27";
        $periodo->fecha_final_1er_reporte = "2024-10-20";
        $periodo->fecha_inicio_2do_reporte = "2024-10-21";
        $periodo->fecha_final_2do_reporte = "2024-11-15";
        $periodo->fecha_inicio_reporte_final = "2024-11-16";
        $periodo->fecha_final_reporte_final = "2024-12-13";
        $periodo->save(); 
    
        $periodo = new Periodo();
        $periodo->nombre = "Enero – Julio 2025";
        $periodo->fecha_inicio = "2025-01-08";
        $periodo->fecha_final = "2025-06-12";
        $periodo->fecha_inicio_1er_reporte = "2025-01-09";
        $periodo->fecha_final_1er_reporte = "2025-03-02";
        $periodo->fecha_inicio_2do_reporte = "2025-03-03";
        $periodo->fecha_final_2do_reporte = "2025-04-25";
        $periodo->fecha_inicio_reporte_final = "2025-04-26";
        $periodo->fecha_final_reporte_final = "2025-06-11";
        $periodo->save(); 
    }
    
}
