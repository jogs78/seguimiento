<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $residente = new Residente();
        $residente->nombre = "Jorge Omner"; 
        $residente->apellido_paterno = "Arias";
        $residente->apellido_materno = "Olea";
        $residente->correo_electronico = "L20270264@tuxtla.tecnm.mx";
        $residente->numero_de_control = "20270264";
        $residente->telefono = "9613599215";
        $residente->direccion = "Paseo de las Gargolas Edif 380 B";
        $residente->institucion_seguridad_social = "IMSS";
        $residente->numero = "1999";
        $residente->save();

        $residente = new Residente();
        $residente->nombre = "Francisco"; 
        $residente->apellido_paterno = "Moreno";
        $residente->apellido_materno = "Martinez";
        $residente->correo_electronico = "L20270254@tuxtla.tecnm.mx";
        $residente->numero_de_control = "L20270254";
        $residente->telefono = "9615369132";
        $residente->direccion = "4ta norte y 2da poniente Edif 4 A";
        $residente->institucion_seguridad_social = "IMSS";
        $residente->numero = "2001";
        $residente->save(); 
    }

}
