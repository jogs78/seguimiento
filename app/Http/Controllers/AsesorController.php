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

    if (!$carrera_id) {
        return redirect()->route('seleccionar.carrera')
            ->with('error', 'Debes seleccionar una carrera primero');
    }

    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');

    $query = Asesor::whereHas('carreras', function ($q) use ($carrera_id) {
        $q->where('carrera_id', $carrera_id);
    });

    if ($buscar) {
        $query->where(function ($q) use ($buscar) {
            $q->where(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscar . '%')
              ->orWhere('nombre', 'like', '%' . $buscar . '%')
              ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
              ->orWhere('apellido_materno', 'like', '%' . $buscar . '%');
        });
    }

    $todos = $query->orderBy('apellido_paterno')
        ->orderBy('apellido_materno')
        ->orderBy('nombre')
        ->get();

    foreach ($todos as $asesor) {
        $asesor->proyectos_del_periodo = $asesor->proyectos($periodo_id)
            ->with(['estudiantes', 'empresa'])
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
        //con inertia
        return Inertia::render('asesor/crear', [
            'asesores' => $asesores
        ]);
        //return view('asesor.crear',compact('asesores'));
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
         return Inertia::render('asesor/editar', [
            'asesor' => $asesor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsesorRequest $request, Asesor $asesor)
    {
        $asesor->fill($request->all());
        $asesor->save();
        
        return redirect()->route("asesores.index")->with('success', 'Asesor actualizado correctamente');
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
        switch ($pagina) {
            case 'no-calificaciones':
                return Inertia::render('asesor/avisos/no-calificacion');
                
            case 'calificaciones':
                return Inertia::render('asesor/calificacion');
                
            case 'proyectos-asignados':
                return redirect()->route('asesor.listar-proyectos');
                
            case 'promedio':
                return Inertia::render('asesor/promedio');
                
            case 'fuera-periodo':
                return Inertia::render('asesor/avisos/fuera-periodo');
                
            default:
                abort(404, 'Página no encontrada');
        }
    }

    public function proyecto()
    {
        $asesor = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $carrera_id = session('carrera_id');
        
        // Cargar proyectos con relaciones necesarias
        $proyectos = $asesor->proyectos($periodo_id)
            ->whereHas('estudiantes', function ($q) use ($carrera_id) {
            if ($carrera_id) {
                    $q->where('carrera_id', $carrera_id);
                }
            })
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
        $carrera_id= session('carrera_id');
        $periodoSeleccionado = $request->input('periodo_id');
        
        // Consulta base usando whereHas
        $proyectosQuery = Proyecto::whereHas('estudiantes', function($q) use ($asesor) {
            $q->where('asesor_id', $asesor->id);
        });
        //aplicar filtro de carrera de la sesión
        if ($carrera_id) {
            $proyectosQuery->whereHas('estudiantes', function($q) use ($carrera_id) {
                $q->where('carrera_id', $carrera_id);
            });
        }

        
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
        
        return Inertia::render($tipo === 'externo' ? 'externo/historico' : 'asesor/historico', [
            'proyectos' => $proyectos,
            'periodos' => $listaPeriodos,
            'periodoSeleccionado' => $periodoSeleccionado,
            'tipo' => $tipo
        ]);
    }

}
