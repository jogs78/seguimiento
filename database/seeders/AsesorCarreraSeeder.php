<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AsesorCarrera;

class AsesorCarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $nueva = new AsesorCarrera();
    $nueva->asesor_id = 1;
    $nueva->carrera_id = 1;
    $nueva->save();

    $nueva = new AsesorCarrera();
    $nueva->asesor_id = 1;
    $nueva->carrera_id = 4;
    $nueva->save();

    $nueva = new AsesorCarrera();
    $nueva->asesor_id = 2;
    $nueva->carrera_id = 1;
    $nueva->save();

    $nueva = new AsesorCarrera();
    $nueva->asesor_id = 2;
    $nueva->carrera_id = 3;
    $nueva->save();
}
}
