<?php

namespace Database\Seeders;
use App\Models\Actividad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $nueva = new Actividad();
        $nueva->nombre = "analisis";
        $nueva->semanas = 2;
        $nueva->save();
        $nueva = new Actividad();
        $nueva->nombre = "diseño";
        $nueva->semanas = 2;
        $nueva->save();
        $nueva = new Actividad();
        $nueva->nombre = "implementacion";
        $nueva->semanas = 2;
        $nueva->save();


    }
}
