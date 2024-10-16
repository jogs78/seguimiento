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
        $nuevo->nombre_usuario = "jorge.gs1@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Asesor";
        $nuevo->save();
        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "L20270264@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Estudiante";
        $nuevo->save();
        $nuevo = new Usuario();
        $nuevo->nombre_usuario = "mario.padilla@tuxtla.tecnm.mx";
        $nuevo->contraseña = Hash::make('1234');
        $nuevo->usa_id=1;
        $nuevo->usa_type = "App\Models\Carrera";
        $nuevo->save();

    }
}
