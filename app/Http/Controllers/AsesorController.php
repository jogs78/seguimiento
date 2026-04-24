<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Proyecto;
use App\Models\Coordinador;
use App\Models\Configuracion;
use App\Http\Requests\StoreAsesorRequest;
use App\Http\Requests\UpdateAsesorRequest;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $carrera_id = session('carrera_id');
        
        // Si no hay carrera seleccionada, redirigir
        if (!$carrera_id) {
            return redirect()->route('seleccionar.carrera')
                ->with('error', 'Debes seleccionar una carrera primero');
        }
        
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        $query = Asesor::whereHas('carreras', function($q) use ($carrera_id) {
            $q->where('carrera_id', $carrera_id);
        });
        
        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscar . '%')
                  ->orWhere('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
                  ->orWhere('apellido_materno', 'like', '%' . $buscar . '%');
            });
        }
        
        $query->orderBy('apellido_paterno')
              ->orderBy('apellido_materno')
              ->orderBy('nombre');
        
        $todos = $query->get();
        
       
        foreach ($todos as $asesor) {
            // Cargar TODOS los proyectos del asesor en el período actual
            $asesor->proyectos_del_periodo = Proyecto::where('asesor_id', $asesor->id)
                ->where('periodo_id', $periodo_id)
                ->with(['estudiantes', 'empresa']) // Cargar relaciones para mostrar más info
                ->get();
        }
                
        return Inertia::render('asesor/listar', [
            'todos' => $todos,
            'filtroBuscar' => $buscar
        ]);
    }

    public function buscarAsesor(Request $request)
    {
        $termino = $request->input('term');
        $carrera_id = session('carrera_id');
        
        if (!$carrera_id) {
            return response()->json([]);
        }
        
        $resultados = Asesor::whereHas('carreras', function($q) use ($carrera_id) {
                $q->where('carrera_id', $carrera_id);
            })
            ->where(function($q) use ($termino) {
                $q->where('nombre', 'like', '%' . $termino . '%')
                  ->orWhere('apellido_paterno', 'like', '%' . $termino . '%')
                  ->orWhere('apellido_materno', 'like', '%' . $termino . '%');
            })
            ->select('id', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->limit(10)
            ->get();

        $sugerencias = $resultados->map(function ($asesor) {
            return [
                'id' => $asesor->id,
                'value' => $asesor->nombre . ' ' . $asesor->apellido_paterno . ' ' . $asesor->apellido_materno,
            ];
        });

        return response()->json($sugerencias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asesores = Asesor::all();
        return view('asesor.crear',compact('asesores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsesorRequest $request)
    {
        $nuevo = new Asesor;
        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("asesores.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Asesor $asesor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asesor $asesor)
    {
        return view('asesor.editar',compact("asesor"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsesorRequest $request, Asesor $asesor)
    {
        $asesor->fill($request->all());
        $asesor->save();
        return redirect()->route("asesores.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asesor $asesor)
    {
         try {
        $asesor->delete();

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

public function mostrar($pagina)
    {
        // Logica para determinar qué vista devolver
        if ($pagina == 'no-calificaciones') {
            return view('asesor.avisos.no-calificacion');
        } elseif ($pagina == 'calificaciones') {
            return view('asesor.calificacion');
        } elseif ($pagina == 'proyectos-asignados') {
            return view('asesor.listar-proyecto');
        } elseif ($pagina == 'promedio') {
            return view('asesor.promedio');
        } elseif ($pagina == 'fuera-periodo') {
        return view('asesor.avisos.fuera-periodo');
        }
        else {
            return abort(404); // Si la página no existe, lanzamos un 404
        }
    }

    public function proyecto()
    {
        $asesor = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        // Cargar proyectos con relaciones necesarias
        $proyectos = $asesor->proyectos($periodo_id)
            ->with([
                'empresa', 
                'externo', 
                'estudiantes.carrera.coordinador', 
                'estudiantes.primer', 
                'estudiantes.segundo', 
                'estudiantes.ultimo'
            ])
            ->get();
        
        $coordinador = Coordinador::all(); // ← Se mantiene igual que en el original
        
        $tipo = get_class($asesor); // "App\Models\Externo" o "App\Models\Asesor"
        
        // Si es externo, usar su vista específica
        if ($tipo == "App\Models\Externo") {
              return Inertia::render('externo/listar-proyecto', [
                'proyectos' => $proyectos,
                'coordinador' => $coordinador
            ]);
        } 
        
        // Si es asesor interno
        return Inertia::render('asesor/listar-proyecto', [
            'proyectos' => $proyectos,
            'coordinador' => $coordinador
        ]);
    }
    
    public function historico(Request $request)
{
    $asesor = Auth::getUser()->usa;
    
    $periodoSeleccionado = $request->input('periodo_id');
    
    // Consulta base usando whereHas
    $proyectosQuery = Proyecto::whereHas('estudiantes', function($q) use ($asesor) {
        $q->where('asesor_id', $asesor->id);
    });
    
    // Aplicar filtro de periodo solo si se seleccionó uno
    if ($periodoSeleccionado) {
        $proyectosQuery->where('periodo_id', $periodoSeleccionado);
    }
    
    $proyectos = $proyectosQuery
        ->with([
            'empresa', 
            'externo', 
            'asesor',
            'periodo',
            'estudiantes.carrera.coordinador', 
            'estudiantes.primer', 
            'estudiantes.segundo', 
            'estudiantes.ultimo'
        ])
        ->get();
    
    $listaPeriodos = \App\Models\Periodo::orderBy('id', 'desc')->get();
    
    $tipo = get_class($asesor) == "App\Models\Externo" ? 'externo' : 'asesor';
    
    return Inertia::render($tipo === 'externo' ? 'externo/HistoricoProyectos' : 'asesor/historico', [
        'proyectos' => $proyectos,
        'periodos' => $listaPeriodos,
        'periodoSeleccionado' => $periodoSeleccionado,
        'tipo' => $tipo
    ]);
}

}
