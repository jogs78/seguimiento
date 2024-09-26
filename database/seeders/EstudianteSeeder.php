<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estudiante = new Estudiante();
        $estudiante->nombre = "Jorge Omner"; 
        $estudiante->apellido_paterno = "Arias";
        $estudiante->apellido_materno = "Olea";
        $estudiante->correo_electronico = "L20270264@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270264";
        $estudiante->telefono = "9613599215";
        $estudiante->direccion = "Paseo de las Gargolas Edif 380 B";
        $estudiante->institucion_seguridad_social = "IMSS";
        $estudiante->numero_de_seguridad_social = "1999";
        $estudiante->save();

        $estudiante = new Estudiante();
        $estudiante->nombre = "Francisco"; 
        $estudiante->apellido_paterno = "Moreno";
        $estudiante->apellido_materno = "Martinez";
        $estudiante->correo_electronico = "L20270254@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "L20270254";
        $estudiante->telefono = "9615369132";
        $estudiante->direccion = "4ta norte y 2da poniente Edif 4 A";
        $estudiante->institucion_seguridad_social = "IMSS";
        $estudiante->numero_de_seguridad_social = "2001";
        $estudiante->save(); 
    }

}
