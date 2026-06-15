<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Carrera;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Proyecto;
use App\Models\Coordinador;
use App\Models\Asesor;
use App\Models\Externo;
use App\Models\Estudiante;
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
    public function salida()
    {
        // Limpiar las variables de carrera de la sesión
        session()->forget(['carrera_id', 'carrera_nombre']);   
        Auth::logout();
        return redirect('/');
    }

    //  Cambiar contraseña
    public function cambio(){
        return Inertia::render('acceso/cambiar-contrasenia'); 
    }

     /**
     * Obtener las carreras disponibles para el usuario autenticado
     */
    private function getCarrerasDisponibles($usuario)
    {
        $tipo = $usuario->usa_type;
        $persona = $usuario->usa;
        
        switch ($tipo) {
            case 'App\\Models\\Coordinador':
                return $persona->carrera; // Relación directa
            
            case 'App\\Models\\Estudiante':
                // Estudiante tiene una sola carrera
                return Carrera::where('id', $persona->carrera_id)->get();
            
            case 'App\\Models\\Asesor':
                // Asesor interno a través de tabla pivote
                return $persona->carreras;
            
            case 'App\\Models\\Externo':
                // Asesor externo a través de proyectos → estudiantes → carrera
                $carrerasIds = Proyecto::where('externo_id', $persona->id)
                    ->with('estudiantes.carrera')
                    ->get()
                    ->flatMap(function($proyecto) {
                        return $proyecto->estudiantes->pluck('carrera_id');
                    })
                    ->unique()
                    ->values()
                    ->toArray();
                
                return Carrera::whereIn('id', $carrerasIds)->get();
            
            default:
                return collect();
        }
    }

    /**
     * Mostrar formulario de selección de carrera
     */
    public function mostrarSeleccionCarrera()
    {
        $usuario = Auth::user();
        $carreras = $this->getCarrerasDisponibles($usuario);
        
        if ($carreras->count() === 1) {
            // Si solo tiene una carrera, guardar automáticamente
            $this->guardarCarreraEnSesion($carreras->first()->id, $carreras->first()->nombre);
            return redirect()->route('home');
        }
        
        return Inertia::render('acceso/SeleccionarCarrera', [
            'carreras' => $carreras,
            'tipo_usuario' => $this->getTipoUsuario($usuario)
        ]);
    }

    /**
     * Guardar carrera seleccionada
     */
    public function seleccionarCarrera(Request $request)
    {
        $request->validate([
            'carrera_id' => 'required|exists:carreras,id'
        ]);
        
        $carrera = Carrera::find($request->carrera_id);
        
        $this->guardarCarreraEnSesion($carrera->id, $carrera->nombre);
        
        return Inertia::render('acceso/adentro');
    }
    
    /**
     * Guardar carrera en sesión
     */
    private function guardarCarreraEnSesion($id, $nombre)
    {
        session(['carrera_id' => $id]);
        session(['carrera_nombre' => $nombre]);
    }
    
    /**
     * Obtener tipo de usuario legible
     */
    private function getTipoUsuario($usuario)
    {
        $tipos = [
            'App\\Models\\Coordinador' => 'coordinador',
            'App\\Models\\Estudiante' => 'estudiante',
            'App\\Models\\Asesor' => 'asesor',
            'App\\Models\\Externo' => 'externo',
        ];
        
        return $tipos[$usuario->usa_type] ?? 'usuario';
    }

    //  Home después del login
    public function home()
    {
        $usuario = Auth::user();

        // Si ya seleccionó una carrera anteriormente
        if (session()->has('carrera_id')) {
            return Inertia::render('acceso/adentro');
        }

        // Obtener carreras disponibles
        $carreras = $this->getCarrerasDisponibles($usuario);

        // Solo una carrera
        if ($carreras->count() === 1) {

            session([
                'carrera_id' => $carreras->first()->id,
                'carrera_nombre' => $carreras->first()->nombre
            ]);

            return Inertia::render('acceso/adentro');
        }

        // Varias carreras
        if ($carreras->count() > 1) {

            return Inertia::render('coordinador/SeleccionarCarrera', [
                'carreras' => $carreras
            ]);
        }

        return Inertia::render('acceso/adentro');
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