<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "JorgeOctavio@tuxtla.tecnm.mx"; //los nombre_usuario son los correos de los usuarios
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Asesor";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "JesusCarlos@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=2;
        $nuevo->usa_type = "App\Models\Asesor";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "NestorAntonio@tuxtla.tecnm.mx"; 
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=3;
        $nuevo->usa_type = "App\Models\Asesor";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "MexicoLed@tuxtla.tecnm.mx"; 
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=4;
        $nuevo->usa_type = "App\Models\Asesor";
        $nuevo->save();


        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "L20270264@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Estudiante";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "Elin@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Coordinador";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "gabriela";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Externo";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "alberto_gg@hotmail.com.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=2;
        $nuevo->usa_type = "App\Models\Externo";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "fco";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=2;
        $nuevo->usa_type = "App\Models\Estudiante";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "jaz";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=3;
        $nuevo->usa_type = "App\Models\Estudiante";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "seidy";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=4;
        $nuevo->usa_type = "App\Models\Estudiante";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "obdulia@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=2;
        $nuevo->usa_type = "App\Models\Coordinador";
        $nuevo->save();

        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "mauricio";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=3;
        $nuevo->usa_type = "App\Models\Externo";
        $nuevo->save();


    }
}
