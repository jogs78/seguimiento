<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seguimiento;

class SeguimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seguimiento = new Seguimiento();
        $seguimiento->primero_promedio = "8";
        $seguimiento->segundo_promedio = "9";
        $seguimiento->final_promedio = "10";
        $seguimiento->save(); 

        $seguimiento = new Seguimiento();
        $seguimiento->primero_promedio = "7";
        $seguimiento->segundo_promedio = "8";
        $seguimiento->final_promedio = "7";
        $seguimiento->save(); 
    }

}
