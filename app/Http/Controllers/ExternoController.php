<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternoRequest;
use App\Http\Requests\UpdateExternoRequest;
use App\Models\Usuario;
use App\Models\Coordinador;
use App\Models\Periodo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Proyecto;
use Inertia\Inertia;

use App\Models\Externo;

class ExternoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $carrera_id = session('carrera_id');
        
        if (!$carrera_id) {
            return redirect()->route('seleccionar.carrera')
                ->with('error', 'Debes seleccionar una carrera primero');
        }
        
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        if (!$periodo_id) {
            return redirect()->back()
                ->with('error', 'No hay un período configurado');
        }
        
        // Obtener período actual para mostrar en la vista
        $periodoActual = Periodo::find($periodo_id);
        
        // Consulta optimizada con joins
        $query = Externo::select('externos.*')
            ->join('proyectos', 'externos.id', '=', 'proyectos.externo_id')
            ->join('estudiantes', 'proyectos.id', '=', 'estudiantes.proyecto_id')
            ->where('proyectos.periodo_id', $periodo_id)
            ->where('estudiantes.carrera_id', $carrera_id)
            ->whereNotNull('proyectos.externo_id')
            ->distinct();
        
        // Aplicar búsqueda si existe
        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('externos.nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('externos.apellido_paterno', 'like', '%' . $buscar . '%')
                  ->orWhere('externos.apellido_materno', 'like', '%' . $buscar . '%')
                  ->orWhere('externos.correo_electronico', 'like', '%' . $buscar . '%')
                  ->orWhere('externos.puesto', 'like', '%' . $buscar . '%')
                  ->orWhere(DB::raw("CONCAT(externos.titulo, ' ', externos.nombre, ' ', externos.apellido_paterno, ' ', externos.apellido_materno)"), 'like', '%' . $buscar . '%');
            });
        }
        
        $query->orderBy('externos.apellido_paterno')
              ->orderBy('externos.apellido_materno')
              ->orderBy('externos.nombre');
        
        $todos = $query->get();
        
        // Cargar el proyecto actual y usuario para cada externo
        // En el controlador, usando query builder directamente
foreach ($todos as $externo) {
    $externo->proyecto_actual = Proyecto::where('externo_id', $externo->id)
        ->where('periodo_id', $periodo_id)
        ->first();
    $externo->usuario = $externo->usuario;
}
        
        return Inertia::render('externo/listar', [
            'todos' => $todos,
            'periodoActual' => $periodoActual,
            'filtroBuscar' => $buscar
        ]);
         


    }

    public function buscarExterno(Request $request)
    {
        $termino = $request->input('term');
        $carrera_id = session('carrera_id');
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        $resultados = Externo::select('externos.id', 'externos.titulo', 'externos.nombre', 'externos.apellido_paterno', 'externos.apellido_materno')
            ->join('proyectos', 'externos.id', '=', 'proyectos.externo_id')
            ->join('estudiantes', 'proyectos.id', '=', 'estudiantes.proyecto_id')
            ->where('proyectos.periodo_id', $periodo_id)
            ->where('estudiantes.carrera_id', $carrera_id)
            ->whereNotNull('proyectos.externo_id')
            ->where(function($q) use ($termino) {
                $q->where('externos.nombre', 'like', '%' . $termino . '%')
                  ->orWhere('externos.apellido_paterno', 'like', '%' . $termino . '%')
                  ->orWhere('externos.apellido_materno', 'like', '%' . $termino . '%')
                  ->orWhere('externos.titulo', 'like', '%' . $termino . '%');
            })
            ->distinct()
            ->limit(10)
            ->get();

        $sugerencias = $resultados->map(function ($est) {
            return [
                'id' => $est->id,
                'value' => trim($est->titulo . ' ' . $est->nombre . ' ' . $est->apellido_paterno . ' ' . $est->apellido_materno),
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
        //con inertia
        /*return Inertia::render('externo/crear', [
            'externos' => $externos
        ]);*/
        
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
    $externo = Auth::getUser()->usa;
    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
    
    
    $proyectos = $externo->proyectos($periodo_id)
        ->with([
            'empresa',
            'asesor',
            'estudiantes.carrera.coordinador', 
            'estudiantes.primer',
            'estudiantes.segundo',
            'estudiantes.ultimo'
        ])
        ->get();
    
    // Procesar coordinadores
    foreach ($proyectos as $proyecto) {
        $coordinadores = [];
        foreach ($proyecto->estudiantes as $estudiante) {
            // Ahora $estudiante->carrera debería existir
            if ($estudiante->carrera && $estudiante->carrera->coordinador) {
                $coordinador = $estudiante->carrera->coordinador;
                $coordinadores[$coordinador->id] = $coordinador;
            }
        }
        $proyecto->coordinador = !empty($coordinadores) ? reset($coordinadores) : null;
    }
    
    return Inertia::render('externo/listar-proyecto', [
        'proyectos' => $proyectos,
        'periodo_id' => $periodo_id
    ]);
}

 public function historico(Request $request)
    {
        $externo = Auth::getUser()->usa;
        
        // Obtener el periodo seleccionado (por defecto null para mostrar todos)
        $periodoSeleccionado = $request->input('periodo_id');
        
        // Construir consulta base usando whereHas
        $proyectosQuery = \App\Models\Proyecto::whereHas('externo', function($q) use ($externo) {
            $q->where('id', $externo->id);
        });
        
        // Aplicar filtro de periodo solo si se seleccionó uno
        if ($periodoSeleccionado) {
            $proyectosQuery->where('periodo_id', $periodoSeleccionado);
        }
        
        $proyectos = $proyectosQuery
            ->with([
                'empresa',
                'asesor',
                'periodo',
                'estudiantes.carrera.coordinador', 
                'estudiantes.primer',
                'estudiantes.segundo',
                'estudiantes.ultimo'
            ])
            ->get();
        
        // Procesar coordinadores para cada proyecto
        foreach ($proyectos as $proyecto) {
            $coordinadores = [];
            foreach ($proyecto->estudiantes as $estudiante) {
                if ($estudiante->carrera && $estudiante->carrera->coordinador) {
                    $coordinador = $estudiante->carrera->coordinador;
                    $coordinadores[$coordinador->id] = $coordinador;
                }
            }
            $proyecto->coordinador = !empty($coordinadores) ? reset($coordinadores) : null;
        }
        
        // Obtener lista de todos los periodos para el selector
        $listaPeriodos = Periodo::orderBy('id', 'desc')->get();
        
        return Inertia::render('externo/historico', [
            'proyectos' => $proyectos,
            'periodos' => $listaPeriodos,
            'periodoSeleccionado' => $periodoSeleccionado,
        ]);
    }

    public function proyecto2()
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
