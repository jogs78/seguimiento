<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use App\Models\Carrera;
use App\Http\Requests\StoreCoordinadorRequest;
use App\Http\Requests\UpdateCoordinadorRequest;
use App\Helpers\DocumentosHelper;
use App\Models\Proyecto;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Configuracion;
use App\Models\Asesor;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\AsesoresExport;
use App\Exports\ExternosExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;


class CoordinadorController extends Controller
{


    /*
    public function tabla(){
        $coordinador = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $proyectos = $coordinador->proyectos( $periodo_id);
        $proyectos = Proyecto::where('periodo_id', $periodo_id)->get();
        $asesores = Asesor::all();
        //return view ('coordinador.tabla', compact('proyectos','asesores'));
        //retornar con inertia
        return Inertia::render('coordinador/tabla', compact('proyectos','asesores'));
    } */


    private function getPeriodoActual()
    {
        // Obtener el periodo_id de configuraciones
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        // Si no hay periodo_id configurado, retornar null
        if (!$periodo_id) {
            return null;
        }
        
        // Buscar el período en la tabla periodos
        $periodo = Periodo::find($periodo_id);
        
        // Retornar el período completo o solo el nombre
        return $periodo;
    }
    
    public function tabla()
    {
        $coordinador = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        // Obtener el período actual
        $periodoActual = Periodo::find($periodo_id);
        
        // Cargar proyectos con TODAS las relaciones necesarias
        $proyectosQuery = Proyecto::where('periodo_id', $periodo_id)
            ->with([
                'asesor',
                'externo',
                'empresa',
                'estudiantes' => function($query) {
                    $query->with([
                        'primer',
                        'segundo',
                        'ultimo',
                        'documentos.tipoDocumento'
                    ]);
                }
            ]);

        // Aplicar filtro por carrera de la sesión
        if (session()->has('carrera_id')) {
            $proyectosQuery->whereHas('estudiantes', function ($q) {
                $q->where('carrera_id', session('carrera_id'));
            });
        }

        $proyectos = $proyectosQuery->get();

       // Procesar documentos usando el Helper
        $documentosProcesados = DocumentosHelper::procesarDocumentosDeProyectos($proyectos);

        // Cargar asesores
        $asesores = Asesor::whereHas('carreras', function ($q) {
            $q->where('carrera_id', session('carrera_id'));
        })->get();

        $internoConfig = Configuracion::where('variable', 'interno')
            ->where('carrera_id', session('carrera_id'))
            ->first();
            
        return Inertia::render('coordinador/tabla', [
            'proyectos' => $proyectos,
            'asesores' => $asesores,
            'periodoActual' => $periodoActual,
            'internoConfig' => $internoConfig,
            'documentosProcesados' => $documentosProcesados  // ← Pasar el array a la vista
        ]);
    }

    public function asignarAsesor1(){
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $proyectos = Proyecto::where('periodo_id', $periodo_id)->get();
        return view ('coordinador.asignar-asesor1', compact('proyectos'));
    }

    public function asignarAsesor2(Request $request){
        
        $proyecto = Proyecto::find($request->proyecto_id);
        $asesores = Asesor::all();
        return view ('coordinador.asignar-asesor2', compact('proyecto','asesores'));
    }
    public function asignarAsesor3(Request $request, $proyecto_id){
        

        //aqui debemos implementar el chequedo
        Log::channel('debug')->info('checar');
        $proyecto = Proyecto::find($proyecto_id);
        if (! Gate::allows('update',$proyecto)){
            return "NO PUEDE ACTUALIZAR";

        }


        $proyecto = Proyecto::find($proyecto_id);
        $proyecto->asesor_id = $request->asesor_id;
        $proyecto->save();
        return redirect(route('coordinadores.tabla'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoordinadorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Coordinador $coordinador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coordinador $coordinador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoordinadorRequest $request, Coordinador $coordinador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coordinador $coordinador)
    {
        //
    }

    public function periodo()
    {
        $coordinador = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');


        return view('coordinador.periodo.listar',compact('periodo_id')); 
    }

    public function historico(Request $request)
    {
        $coordinador = Auth::getUser()->usa;
        $carrera_id = session('carrera_id');
        
        // Obtener el periodo seleccionado (por defecto null para mostrar todos)
        $periodoSeleccionado = $request->input('periodo_id');
        
        // Construir consulta base
        $proyectosQuery = Proyecto::with([
            'asesor',
            'externo',
            'empresa',
            'periodo',
            'estudiantes' => function($query) {
                $query->with([
                    'primer',
                    'segundo',
                    'ultimo'
                ]);
            }
        ]);
        
        // Aplicar filtro por carrera de la sesión (si existe)
        if ($carrera_id) {
            $proyectosQuery->whereHas('estudiantes', function ($q) use ($carrera_id) {
                $q->where('carrera_id', $carrera_id);
            });
        }
        
        // Aplicar filtro de periodo si se seleccionó uno
        if ($periodoSeleccionado) {
            $proyectosQuery->where('periodo_id', $periodoSeleccionado);
        }
        
        $proyectos = $proyectosQuery->get();
        
        // Obtener lista de todos los periodos para el selector
        $listaPeriodos = Periodo::orderBy('id', 'desc')->get();
        
        // Obtener periodo actual para referencia
        $periodoActual = Periodo::find(ConfiguracionServiceProvider::get('periodo_id'));
        
        return Inertia::render('coordinador/historico', [
            'proyectos' => $proyectos,
            'periodos' => $listaPeriodos,
            'periodoSeleccionado' => $periodoSeleccionado,
            'periodoActual' => $periodoActual,
            'carreraActual' => session('carrera_id') ? [
                'id' => session('carrera_id'),
                'nombre' => session('carrera_nombre')
            ] : null
        ]);
    }

    public function estudiante()
    {
        $coordinador = Auth::getUser()->usa;
        $estudiante_id = ConfiguracionServiceProvider::get('estudiante_id');
        return view('coordinador.listar-estudiantes',compact('estudiante_id')); 
    } 

    public function asesores(){
        $coordinador = Auth::getUser()->usa;
        $asesor_id = ConfiguracionServiceProvider::get('asesor_id');
        return view('coordinador.listar-asesores',compact('asesor_id')); 
    }

    public function exportarLista(){
        return Excel::download(new UsersExport, 'Estudiantes.xlsx');
    }

    public function exportandoLista()
    {
        $carrera_id = session('carrera_id');
        
        if (!$carrera_id) {
            return redirect()->back()->with('error', 'Debes seleccionar una carrera primero');
        }
        
        return Excel::download(new AsesoresExport($carrera_id), 'Asesores_Internos.xlsx');
    }

    public function exportLista(){
        return Excel::download(new ExternosExport, 'Asesores_Externos.xlsx');
    }

    //agregando nuevo
    /*
    public function seleccionarCarrera(Request $request)
    {
    session(['carrera_id' => $request->carrera_id]);
    //return view('acceso.adentro');
    
     return Inertia::render('acceso/adentro');//ruta para Inertia
      //return redirect()->route('adentro'); //  ruta para Blade

    }

*/
public function seleccionarCarrera(Request $request)
{
    // Validar que la carrera existe
    $request->validate([
        'carrera_id' => 'required|exists:carreras,id'
    ]);
    
    // Obtener la carrera completa
    $carrera = Carrera::find($request->carrera_id);
    
    // Guardar en sesión TANTO el ID como el NOMBRE
    session(['carrera_id' => $request->carrera_id]);
    session(['carrera_nombre' => $carrera->nombre]); // ← Esto es crucial
    
    // También puedes guardar el objeto completo si prefieres
    // session(['carrera_actual' => $carrera]);
    
    // Redirigir al dashboard
    //return redirect()->route('welcome'); // o 'adentro'
    return Inertia::render('acceso/adentro');//ruta para Inertia
}

}
