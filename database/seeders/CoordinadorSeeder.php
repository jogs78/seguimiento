<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coordinador;

class CoordinadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nueva = new Coordinador();
        $nueva->nombre = "Obdulia";
        $nueva->apellido_paterno = "Rios";
        $nueva->apellido_materno = "Coutiño";
        $nueva->correo_electronico = "CSistemas@gmail.com";
        $nueva->carrera_id = 1;
        $nueva->save();

        $nueva = new Coordinador();
        $nueva->nombre = "Mario";
        $nueva->apellido_paterno = "de la Cruz";
        $nueva->apellido_materno = "Padilla";
        $nueva->correo_electronico = "cmecanica@gmail.com";
        $nueva->carrera_id = 2;
        $nueva->save();
    }
}
