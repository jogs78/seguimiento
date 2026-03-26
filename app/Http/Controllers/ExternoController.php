<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternoRequest;
use App\Http\Requests\UpdateExternoRequest;
use App\Models\Usuario;
use App\Models\Coordinador;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Proyecto;

use App\Models\Externo;

class ExternoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $buscar = $request->input('buscar');
    $carrera_id = session('carrera_id'); // Obtener carrera de la sesión
    
    if (!$carrera_id) {
        return redirect()->route('seleccionar.carrera')
            ->with('error', 'Debes seleccionar una carrera primero');
    }
    
    // 1. Primero, obtener los IDs de proyectos que tienen estudiantes con la carrera seleccionada
    $proyectosIds = Estudiante::where('carrera_id', $carrera_id)
        ->whereNotNull('proyecto_id')
        ->pluck('proyecto_id')
        ->unique();
    
    // 2. Obtener los IDs de asesores externos que están en esos proyectos
    $externosIds = Proyecto::whereIn('id', $proyectosIds)
        ->whereNotNull('externo_id')
        ->pluck('externo_id')
        ->unique();
    
    // 3. Construir la consulta base para asesores externos
    $query = Externo::whereIn('id', $externosIds);
    
    // 4. Aplicar búsqueda si existe
    if ($buscar) {
        $query->where(function($q) use ($buscar) {
            $q->where('nombre', 'like', '%' . $buscar . '%')
              ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
              ->orWhere('apellido_materno', 'like', '%' . $buscar . '%')
              ->orWhere('correo_electronico', 'like', '%' . $buscar . '%')
              ->orWhere('puesto', 'like', '%' . $buscar . '%')
              ->orWhere(DB::raw("CONCAT(titulo, ' ', nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscar . '%');
        });
    }
    
    $todos = $query->get();
    
    return view('externo.listar', compact('todos'));
}

     public function buscarExterno(Request $request)
    {
        $termino = $request->input('term');

        $resultados = Externo::where('titulo', 'like', '%' . $termino . '%')
            ->orWhere('nombre', 'like', '%' . $termino . '%')
            ->orWhere('apellido_paterno', 'like', '%' . $termino . '%')
            ->orWhere('apellido_materno', 'like', '%' . $termino . '%')
            ->select('id', 'titulo', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->limit(10)
            ->get();

        // Devuelve el nombre completo como sugerencia
        $sugerencias = $resultados->map(function ($est) {
            return [
                'id' => $est->id,
                'value' => $est->nombre . ' ' . $est->apellido_paterno . ' ' . $est->apellido_materno,
            ];
        });

        return response()->json($sugerencias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $externos = Externo::all();
        return view('externo.crear',compact('externos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExternoRequest $request)
    {
        $nuevo = new Externo;
        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("externos.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Externo $externo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Externo $externo)
    {
        return view('externo.editar',compact("externo"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExternoRequest $request, Externo $externo)
    {
        $externo->fill($request->all());
        $externo->save();
        return redirect()->route("externos.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Externo $externo)
    {
        try {
        $externo->delete();

        return redirect()
            ->back()
            ->with('success', 'Asesor eliminado correctamente');

    } catch (\Exception $e) {

        // Cualquier otro error general
        return redirect()
            ->back()
            ->with('error', 'El asesor no se puede eliminar porque tiene proyectos asignados');
    }
    }

    public function crearCuenta(Externo $externo)
    {
        $usr = new Usuario();
        $usr->usa_id=$externo->id;
        $usr->usa_type = get_class($externo);
        $usr->nombre_usuario = $externo->correo_electronico;
        $usr->contraseña = Hash::make($externo->correo_electronico);
        $usr->save();
        return redirect(route('externos.index'));


    }

    public function proyecto()
    {
        $asesor = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $proyectos= $asesor->proyectos($periodo_id)->get();
        $coordinador = Coordinador::all();

//        dd(get_class($asesor));
        if(get_class($asesor)=="App\Models\Externo" )
            return view('externo.listar-proyecto',compact('proyectos','coordinador')); 
        else
            return view('asesor.lista-de-proyecto',compact('proyectos','coordinador')); 

    }

    
}
