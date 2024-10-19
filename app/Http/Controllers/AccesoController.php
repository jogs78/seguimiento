<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccesoController extends Controller
{
    public function login(){
        return view('acceso.formulario');
    }

    public function salida(){
        return view('acceso.adios');
    }

    public function cambio(){
        return view('acceso.cambiar-contraseña');
    }

    public function adentro(Request $peticion){
/*
        echo "<br>N:" . $peticion->input("nombre");
        echo "<br>C:" . $peticion->input("contra");
*/
        $datos = $peticion->all();
//        echo "<br>N" . $datos["nombre"];
//        echo "<br>C" . $datos["contra"];

        $nombre = $datos["nombre"];
        $contraseña_dada =  $datos["contra"];
        $encontrado = Usuario::where('nombre_usuario',$nombre)->first();

        if (is_null($encontrado)){
            echo "no hay resultados";
        }else{
            //echo "si hay resultados entonces ahora checar la contraseña";
            $contraseña_encriptada = $encontrado->contraseña;
            $comparacion = Hash::check($contraseña_dada, $contraseña_encriptada);
            if($comparacion ){
                //IGUALES
                //guardar el usuario
                Auth::login($encontrado);
                return view('acceso.adentro');
            }else{
                //DIFIERENTES
                return redirect()->back();
            }


        } 

//        return view('acceso.adentro');
    }


    public function registro(){
        return view('alumno.registro');
    }

    public function periodo(){
        return view('coordinador.crear-periodo');
    }

    public function reporte(){
        //puedo saber quie es el usuario que entro
        $usuario = Auth::getUser();
        dd($usuario);
        echo "bienvenido " . $usuario->usa->nombre;

//        return view('alumno.reporte-proyecto');
    }

    public function estatus(){
        return view('coordinador.estatus-alumno');
    }

}
