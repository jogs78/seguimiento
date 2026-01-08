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
        $nueva->nombre = "Elin Enrique";
        $nueva->apellido_paterno = "Aguilar";
        $nueva->apellido_materno = "Molina";
        $nueva->correo_electronico = "elin.am@tuxtla.tecnm.mx";
        $nueva->carrera_id = 2;
        $nueva->save();

        $nueva = new Coordinador();
        $nueva->nombre = "Obdulia";
        $nueva->apellido_paterno = "Rios";
        $nueva->apellido_materno = "Coutiño";
        $nueva->correo_electronico = "csistemas@gmail.com";
        $nueva->carrera_id = 1;
        $nueva->save();


        $nueva = new Coordinador();
        $nueva->nombre = "Teodoro Elmer";
        $nueva->apellido_paterno = "Solis";
        $nueva->apellido_materno = "Tercero";
        $nueva->correo_electronico = "TEST@tuxtla.tecnm.mx";
        $nueva->carrera_id = 3;
        $nueva->save();

        $nueva = new Coordinador();
        $nueva->nombre = "Veronica";
        $nueva->apellido_paterno = "Velasco";
        $nueva->apellido_materno = "Martinez";
        $nueva->correo_electronico = "CIndustrial@gmail.com";
        $nueva->carrera_id = 4;
        $nueva->save();

        $nueva = new Coordinador();
        $nueva->nombre = "Rogelio";
        $nueva->apellido_paterno = "Bistrain";
        $nueva->apellido_materno = "Gutierrez";
        $nueva->correo_electronico = "CElectrica@gmail.com";
        $nueva->carrera_id = 5;
        $nueva->save();
    }
}
