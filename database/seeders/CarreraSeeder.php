<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nueva = new Carrera();
        $nueva->nombre = "Sistemas";
        $nueva->coordinador_id = 2;
        $nueva->save();

        $nueva = new Carrera();
        $nueva->nombre = "Mecanica";
        $nueva->coordinador_id = 1;
        $nueva->save();

        $nueva = new Carrera();
        $nueva->nombre = "Electronica";
        $nueva->coordinador_id = 3;
        $nueva->save();

        $nueva = new Carrera();
        $nueva->nombre = "Industrial";
        $nueva->coordinador_id = 4;
        $nueva->save();

        $nueva = new Carrera();
        $nueva->nombre = "Electrica";
        $nueva->coordinador_id = 5;
        $nueva->save();

        $nueva = new Carrera();
        $nueva->nombre = "Ciberseguridad";
        $nueva->coordinador_id = 2;
        $nueva->save();
        
    }

    
}
