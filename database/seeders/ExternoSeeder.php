<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Externo;

class ExternoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asesor = new Externo();
        $asesor->id = 1;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Gabriela Alejandra";
        $asesor->apellido_paterno = "Ríos";
        $asesor->apellido_materno = "Zúñiga";
        $asesor->correo_electronico = "gabriela.rz1@tuxtla.tecnm.mx";
        $asesor->puesto = "Jefa del Departamento de Departamento de Ingenierías";
        $asesor->save(); 

        $asesor = new Externo();
        $asesor->id = 2;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Alberto";
        $asesor->apellido_paterno = "Gomez";
        $asesor->apellido_materno = "Gonzales";
        $asesor->correo_electronico = "alberto_gg@hotmail.com.mx";
        $asesor->puesto = "Secretario Administrativo";
        $asesor->save(); 

        $asesor = new Externo();
        $asesor->id = 3;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Mauruicio";
        $asesor->apellido_paterno = "Montes";
        $asesor->apellido_materno = "Becerra";
        $asesor->correo_electronico = "mauruicio.mb@hotmail.com.mx";
        $asesor->puesto = "Asistente de operaciones";
        $asesor->save(); 

        $asesor = new Externo();
        $asesor->id = 4;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "María Fernanda";
        $asesor->apellido_paterno = "López";
        $asesor->apellido_materno = "Díaz";
        $asesor->correo_electronico = "maria.ld@hotmail.com.mx";
        $asesor->puesto = "Secretaria asistente";
        $asesor->save(); 

        $asesor = new Externo();
        $asesor->id = 5;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Ana Paula";
        $asesor->apellido_paterno = "Ramírez";
        $asesor->apellido_materno = "Morales";
        $asesor->correo_electronico = "Ana.RM@hotmail.com.mx";
        $asesor->puesto = "Asistente de Desarrollo";
        $asesor->save();

        $asesor = new Externo();
        $asesor->id = 6;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Javier Eduardo";
        $asesor->apellido_paterno = "Salazar";
        $asesor->apellido_materno = "Pérez";
        $asesor->correo_electronico = "Javier_Eduardo@hotmail.com.mx";
        $asesor->puesto = "Tesorero";
        $asesor->save();

        $asesor = new Externo();
        $asesor->id = 7;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Nicolás Alejandro";
        $asesor->apellido_paterno = "Fernández";
        $asesor->apellido_materno = "Ruiz";
        $asesor->correo_electronico = "Nicolás_Alejandro@hotmail.com.mx";
        $asesor->puesto = "Asistente Admistrativo";
        $asesor->save();

        $asesor = new Externo();
        $asesor->id = 8;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "José Francisco";
        $asesor->apellido_paterno = "Trujillo";
        $asesor->apellido_materno = "Ochoa";
        $asesor->correo_electronico = "Francisco_TO@hotmail.com.mx";
        $asesor->puesto = "Consejero Asistente";
        $asesor->save();

        $asesor = new Externo();
        $asesor->id = 9;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Daniela";
        $asesor->apellido_paterno = "Robles";
        $asesor->apellido_materno = "Cruz";
        $asesor->correo_electronico = "DanielaRCruz@hotmail.com.mx";
        $asesor->puesto = "Asesora financiera";
        $asesor->save();

        $asesor = new Externo();
        $asesor->id = 10;
        $asesor->titulo = "Ing.";
        $asesor->nombre = "Alfonso";
        $asesor->apellido_paterno = "Mena";
        $asesor->apellido_materno = "Becerra";
        $asesor->correo_electronico = "DanielaRCruz@hotmail.com.mx";
        $asesor->puesto = "Asesor de Ventas e imagen";
        $asesor->save();
    }
}
