<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccesoController extends Controller
{
    public function entrada(){
        return view('acceso.formulario');
    }

    public function salida(){
        return view('acceso.adios');
    }

    public function cambio(){
        return view('acceso.cambiar-contraseña');
    }

    public function adentro(){
        return view('acceso.adentro');
    }

}
