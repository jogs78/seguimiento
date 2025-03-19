<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Periodo;

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

        $periodo = new Periodo();
        $periodo->nombre = "Agosto - Diciembre 2023";
        $periodo->fecha_inicio = "2023-08-26";
        $periodo->fecha_final = "2023-12-15";
        $periodo->fecha_inicio_1er_reporte = "2023-08-27";
        $periodo->fecha_final_1er_reporte = "2023-10-20";
        $periodo->fecha_inicio_2do_reporte = "2023-10-21";
        $periodo->fecha_final_2do_reporte = "2023-11-15";
        $periodo->fecha_inicio_reporte_final = "2023-11-16";
        $periodo->fecha_final_reporte_final = "2023-12-13";
        $periodo->save();

        $periodo = new Periodo();
        $periodo->nombre = "Enero – Julio 2023";
        $periodo->fecha_inicio = "2023-01-08";
        $periodo->fecha_final = "2023-06-12";
        $periodo->fecha_inicio_1er_reporte = "2023-01-09";
        $periodo->fecha_final_1er_reporte = "2023-03-02";
        $periodo->fecha_inicio_2do_reporte = "2023-03-03";
        $periodo->fecha_final_2do_reporte = "2023-04-25";
        $periodo->fecha_inicio_reporte_final = "2023-04-26";
        $periodo->fecha_final_reporte_final = "2023-06-11";
        $periodo->save(); 

        $periodo = new Periodo();
        $periodo->nombre = "Agosto - Diciembre 2022";
        $periodo->fecha_inicio = "2022-08-26";
        $periodo->fecha_final = "2022-12-15";
        $periodo->fecha_inicio_1er_reporte = "2022-08-27";
        $periodo->fecha_final_1er_reporte = "2022-10-20";
        $periodo->fecha_inicio_2do_reporte = "2022-10-21";
        $periodo->fecha_final_2do_reporte = "2022-11-15";
        $periodo->fecha_inicio_reporte_final = "2022-11-16";
        $periodo->fecha_final_reporte_final = "2022-12-13";
        $periodo->save();

        $periodo = new Periodo();
        $periodo->nombre = "Enero – Julio 2022";
        $periodo->fecha_inicio = "2022-01-08";
        $periodo->fecha_final = "2022-06-12";
        $periodo->fecha_inicio_1er_reporte = "2022-01-09";
        $periodo->fecha_final_1er_reporte = "2022-03-02";
        $periodo->fecha_inicio_2do_reporte = "2022-03-03";
        $periodo->fecha_final_2do_reporte = "2022-04-25";
        $periodo->fecha_inicio_reporte_final = "2022-04-26";
        $periodo->fecha_final_reporte_final = "2022-06-11";
        $periodo->save();

        $periodo = new Periodo();
        $periodo->nombre = "Agosto - Diciembre 2021";
        $periodo->fecha_inicio = "2021-08-26";
        $periodo->fecha_final = "2021-12-15";
        $periodo->fecha_inicio_1er_reporte = "2021-08-27";
        $periodo->fecha_final_1er_reporte = "2021-10-20";
        $periodo->fecha_inicio_2do_reporte = "2021-10-21";
        $periodo->fecha_final_2do_reporte = "2021-11-15";
        $periodo->fecha_inicio_reporte_final = "2021-11-16";
        $periodo->fecha_final_reporte_final = "2021-12-13";
        $periodo->save();

        $periodo = new Periodo();
        $periodo->nombre = "Enero – Julio 2021";
        $periodo->fecha_inicio = "2021-01-08";
        $periodo->fecha_final = "2021-06-12";
        $periodo->fecha_inicio_1er_reporte = "2021-01-09";
        $periodo->fecha_final_1er_reporte = "2021-03-02";
        $periodo->fecha_inicio_2do_reporte = "2021-03-03";
        $periodo->fecha_final_2do_reporte = "2021-04-25";
        $periodo->fecha_inicio_reporte_final = "2021-04-26";
        $periodo->fecha_final_reporte_final = "2021-06-11";
        $periodo->save();

        $periodo = new Periodo();
        $periodo->nombre = "Agosto - Diciembre 2020";
        $periodo->fecha_inicio = "2020-08-26";
        $periodo->fecha_final = "2020-12-15";
        $periodo->fecha_inicio_1er_reporte = "2020-08-27";
        $periodo->fecha_final_1er_reporte = "2020-10-20";
        $periodo->fecha_inicio_2do_reporte = "2020-10-21";
        $periodo->fecha_final_2do_reporte = "2020-11-15";
        $periodo->fecha_inicio_reporte_final = "2020-11-16";
        $periodo->fecha_final_reporte_final = "2020-12-13";
        $periodo->save();

        $periodo = new Periodo();
        $periodo->nombre = "Enero – Julio 2020";
        $periodo->fecha_inicio = "2020-01-08";
        $periodo->fecha_final = "2020-06-12";
        $periodo->fecha_inicio_1er_reporte = "2020-01-09";
        $periodo->fecha_final_1er_reporte = "2020-03-02";
        $periodo->fecha_inicio_2do_reporte = "2020-03-03";
        $periodo->fecha_final_2do_reporte = "2020-04-25";
        $periodo->fecha_inicio_reporte_final = "2020-04-26";
        $periodo->fecha_final_reporte_final = "2020-06-11";
        $periodo->save();
    }
    
}
