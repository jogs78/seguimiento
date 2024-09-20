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
        $nueva->coordinador = "Obdulia Rios Coutiño"; 
        $nueva->save();

        $nueva = new Carrera();
        $nueva->nombre = "Mecanica";
        $nueva->coordinador = "Mario de la Cruz Padilla"; 
        $nueva->save();

    }
}
