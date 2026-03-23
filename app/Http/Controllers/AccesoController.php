<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Inertia\Inertia;

class AccesoController extends Controller
{
    //  Login - minúsculas (acceso/formulario)
    public function login(){
        return Inertia::render('acceso/Formulario', [
            'logoUrl' => asset('images/logo.png')
        ]);
    }

    //  Logout
    public function salida(){
        Auth::logout();
        return redirect('/');
    }

    //  Cambiar contraseña
    public function cambio(){
        return Inertia::render('acceso/cambiar-contrasena'); // ← minúsculas, con guiones
    }

    //  Home después del login
    public function home(){

    $usuario = Auth::user();

    if($usuario->usa_type == "App\Models\Coordinador"){

        $coordinador = $usuario->usa;

        $carreras = $coordinador->carrera;

        if($carreras->count() > 1){

            return Inertia::render('coordinador/SeleccionarCarrera', [
                'carreras' => $carreras
            ]);

        }

        if($carreras->count() == 1){
           // echo "Solo hay una carrera, se seleccionará automáticamente: " . $carreras->first()->nombre;
            session(['carrera_id' => $carreras->first()->id]);
            session(['carrera_nombre' => $carreras->first()->nombre]);
        }
    }

    
   

    //para en caso de que sea estudiante, aparte de la vista enviar una variable que diga si tiene proyecto asignado
     
    /*if($usuario->usa_type == "App\Models\Estudiante"){
        $tieneProyecto = false;

         $estudiante = $usuario->usa;
     
        // Verifica si tiene proyecto
        $tieneProyecto = $estudiante->proyecto ? true : false;
         return view('acceso.adentro', compact('tieneProyecto'));
     }
*/
    return view('acceso.adentro');
    }

    /*
    public function home(){
        return view('acceso.adentro');
    }*/

    //  Procesar login (NO CAMBIA NADA)
    public function adentro(Request $peticion){
        $peticion->validate([
            'nombre' => 'required',
            'contra' => 'required',
        ], [
            'nombre.required' => 'Ingrese su correo.',
            'contra.required' => 'La contraseña es obligatoria.',
        ]);

        $datos = $peticion->all();
        $nombre = $datos["nombre"];
        $contraseña_dada =  $datos["contra"];
        $encontrado = Usuario::where('nombre_usuario', $nombre)->first();

        if (is_null($encontrado)){
            echo 'Contraseña correcta';
            return back()->with('errorsesion', 'Correo no encontrado');
        } else {
            $contraseña_encriptada = $encontrado->contraseña;
            $comparacion = Hash::check($contraseña_dada, $contraseña_encriptada);
            
            if($comparacion){
                echo 'Contraseña correcta';
                Auth::login($encontrado);
                return redirect()->intended(route('home'))->with('success', '¡Bienvenido!');
            } else {
                return back()->with('errorcontra', 'Contraseña incorrecta');
            }
        }
    }

    // Registro alumno
    public function registro(){
        return Inertia::render('alumno/Registro');
    }

    // Crear período (coordinador)
    public function periodo(){
        return Inertia::render('coordinador/crear-periodo');
    }

    // Reporte proyecto
    public function reporte(){
        $usuario = Auth::getUser();
        return Inertia::render('alumno/reporte-proyecto', [
            'usuario' => $usuario
        ]);
    }

    // Estatus alumno
    public function estatus(){
        return Inertia::render('coordinador/estatus-alumno');
    }

    // Layout app
    public function plantilla(){
        return Inertia::render('layouts/app');
    }
}