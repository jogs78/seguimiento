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
        $estudiante->proyecto_id=1;
        $estudiante->carrera_id = 1;
        $estudiante->save();

        $estudiante = new Estudiante();
        $estudiante->nombre = "Francisco"; 
        $estudiante->apellido_paterno = "Moreno";
        $estudiante->apellido_materno = "Martinez";
        $estudiante->correo_electronico = "L20270254@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270254";
        $estudiante->telefono = "9615369132";
        $estudiante->direccion = "4ta norte y 2da poniente Edif 4 A";
        $estudiante->institucion_seguridad_social = "IMSS";
        $estudiante->numero_de_seguridad_social = "2004";
        $estudiante->proyecto_id=11;
        $estudiante->carrera_id = 1;
        $estudiante->save();

        $estudiante = new Estudiante();
        $estudiante->nombre = "Sara Jazmin"; 
        $estudiante->apellido_paterno = "Abarca";
        $estudiante->apellido_materno = "Aguilar";
        $estudiante->correo_electronico = "L20270269@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270269";
        $estudiante->telefono = "9615369953";
        $estudiante->direccion = "1ra norte y 3ra oriente Edif 1 B";
        $estudiante->institucion_seguridad_social = "ISSSTE";
        $estudiante->numero_de_seguridad_social = "2002";
        $estudiante->proyecto_id=3;
        $estudiante->carrera_id = 2;
        $estudiante->save();

        $estudiante = new Estudiante();
        $estudiante->nombre = "Seidy Zayetsy"; 
        $estudiante->apellido_paterno = "Hernández";
        $estudiante->apellido_materno = "Sánchez";
        $estudiante->correo_electronico = "L20270360@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270360";
        $estudiante->telefono = "9615398512";
        $estudiante->direccion = "10ma sur y 10ma oriente Edif 10 A";
        $estudiante->institucion_seguridad_social = "ISSSTE";
        $estudiante->numero_de_seguridad_social = "2003";
        $estudiante->proyecto_id = 8;
        $estudiante->carrera_id = 2;
        $estudiante->save();
        
        $estudiante = new Estudiante();
        $estudiante->nombre = "Ivan Alberto"; 
        $estudiante->apellido_paterno = "Adriano";
        $estudiante->apellido_materno = "Ordaz";
        $estudiante->correo_electronico = "L20270756@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270756";
        $estudiante->telefono = "9614958541";
        $estudiante->direccion = "6ta sur y 5ta oriente";
        $estudiante->institucion_seguridad_social = "IMSS";
        $estudiante->numero_de_seguridad_social = "2001";
        $estudiante->proyecto_id=3;
        $estudiante->carrera_id = 2;
        $estudiante->save(); 

        $estudiante = new Estudiante();
        $estudiante->nombre = "Axel Leonardo"; 
        $estudiante->apellido_paterno = "Aguliar";
        $estudiante->apellido_materno = "Escobar";
        $estudiante->correo_electronico = "L20270270@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270270";
        $estudiante->telefono = "9614950001";
        $estudiante->direccion = "4ta sur y 4ta oriente";
        $estudiante->institucion_seguridad_social = "ISSSTE";
        $estudiante->numero_de_seguridad_social = "2000";
        $estudiante->proyecto_id=4;
        $estudiante->carrera_id = 1;
        $estudiante->save(); 

        $estudiante = new Estudiante();
        $estudiante->nombre = "Oscar Adrian"; 
        $estudiante->apellido_paterno = "Ballinas";
        $estudiante->apellido_materno = "Moguel";
        $estudiante->correo_electronico = "L20270760@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270760";
        $estudiante->telefono = "9611118512";
        $estudiante->direccion = "3ra sur y 3ra oriente Edif 3 A";
        $estudiante->institucion_seguridad_social = "ISSSTE";
        $estudiante->numero_de_seguridad_social = "2007";
        $estudiante->proyecto_id=5;
        $estudiante->carrera_id = 2;
        $estudiante->save();
        
        $estudiante = new Estudiante();
        $estudiante->nombre = "Jesus Emmanuel"; 
        $estudiante->apellido_paterno = "Barrios";
        $estudiante->apellido_materno = "Gutiérrez";
        $estudiante->correo_electronico = "L20270794@tuxtla.tecnm.mx";
        $estudiante->numero_de_control = "20270794";
        $estudiante->telefono = "9614958222";
        $estudiante->direccion = "2da norte y 2da poniente";
        $estudiante->institucion_seguridad_social = "IMSS";
        $estudiante->numero_de_seguridad_social = "2020";
        $estudiante->proyecto_id=5;
        $estudiante->carrera_id = 2;
        $estudiante->save(); 
    }

}
