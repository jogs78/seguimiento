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

    public function login(){
        return view('alumno.login');
    }

    public function registro(){
        return view('alumno.registro');
    }

    public function periodo(){
        return view('coordinador.crear-periodo');
    }

    public function reporte(){
        return view('alumno.reporte-proyecto');
    }

    public function estatus(){
        return view('coordinador.estatus-alumno');
    }

}
