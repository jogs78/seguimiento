<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;
use App\Models\Periodo;
use App\Models\Asesor;
use App\Models\Externo;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Actividad;
use App\Models\Cronograma;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\Estudiante;
use Inertia\Inertia;
use App\Helpers\DocumentosHelper;
use App\Services\DocumentoAutomaticoService;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscarEstudiante = $request->input('buscar');
        $buscarProyecto = $request->input('buscar_proyecto');
        $buscarAsesor = trim($request->input('buscar_asesor'));
        $buscarEmpresa = $request->input('buscar_empresa');

        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $carrera_id = session('carrera_id');

        // Función helper para aplicar filtro de carrera
        $aplicarFiltroCarrera = function($query) use ($carrera_id) {

            if ($carrera_id) {
                $query->where('carrera_id', $carrera_id);
            }
            return $query;
        };

        $proyectos = Proyecto::with([
                'empresa',
                'asesor',
                'externo',
                'estudiantes'
            ])
            ->where('periodo_id', $periodo_id)

            // FILTRO BASE POR CARRERA
            ->when($carrera_id, function ($query) use ($carrera_id) {
                return $query->whereHas('estudiantes', function ($q) use ($carrera_id) {
                    $q->where('carrera_id', $carrera_id);
                });
            })

            // BUSCAR ESTUDIANTE
            ->when($buscarEstudiante, function ($query, $buscarEstudiante) use ($aplicarFiltroCarrera) {
                $query->whereHas('estudiantes', function ($q) use ($buscarEstudiante, $aplicarFiltroCarrera) {
                    $aplicarFiltroCarrera($q);
                    $q->whereRaw("
                        CONCAT(
                            nombre, ' ',
                            apellido_paterno, ' ',
                            apellido_materno
                        ) LIKE ?
                    ", ["%{$buscarEstudiante}%"]);

                });
            })
            // BUSCAR PROYECTO
            ->when($buscarProyecto, function ($query, $buscarProyecto) use ($aplicarFiltroCarrera) {
                $query->where('nombre', 'like', "%{$buscarProyecto}%")
                    ->whereHas('estudiantes', $aplicarFiltroCarrera);
            })
            // BUSCAR ASESOR
            ->when($buscarAsesor, function ($query) use ($buscarAsesor, $aplicarFiltroCarrera) {
                $query->whereHas('asesor', function ($q) use ($buscarAsesor) {
                    $q->whereRaw("
                        CONCAT(
                            nombre, ' ',
                            apellido_paterno, ' ',
                            apellido_materno
                        ) LIKE ?
                    ", ["%{$buscarAsesor}%"]);
                })->whereHas('estudiantes', $aplicarFiltroCarrera);
            })
            // BUSCAR EMPRESA
            ->when($buscarEmpresa, function ($query, $buscarEmpresa) use ($aplicarFiltroCarrera) {
                $query->whereHas('empresa', function ($q) use ($buscarEmpresa) {
                    $q->where('nombre', 'like', "%{$buscarEmpresa}%");
                })->whereHas('estudiantes', $aplicarFiltroCarrera);
            })
            ->get();
        // Procesar documentos
        $documentosProcesados = DocumentosHelper::procesarDocumentosDeProyectos($proyectos);
        $asesores = Asesor::all();
        return Inertia::render('coordinador/tabla', [
            'proyectos' => $proyectos,
            'asesores' => $asesores,
            'filtroBuscarEstudiante' => $buscarEstudiante,
            'filtroBuscarProyecto' => $buscarProyecto,
            'filtroBuscarAsesor' => $buscarAsesor,
            'filtroBuscarEmpresa' => $buscarEmpresa,
            'documentosProcesados' => $documentosProcesados
        ]);
    }

    public function sugerencias(Request $request)
    {
        $query = $request->input('query');
        $carrera_id = session('carrera_id');
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        if (!$carrera_id) {
            return response()->json([]);
        }

        $estudiantes = Estudiante::where('carrera_id', $carrera_id)
            ->whereHas('proyecto', function($q) use ($periodo_id) {  // ← Opcional: filtrar por periodo
                $q->where('periodo_id', $periodo_id);
            })
            ->whereRaw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ["%{$query}%"])
            ->limit(10)
            ->pluck(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre_completo"));

        return response()->json($estudiantes);
    }

    public function sugerenciasEmpresa(Request $request)
    {
        $query = $request->input('query');
        $carrera_id = session('carrera_id');
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        if (!$carrera_id) {
            return response()->json([]);
        }

        $empresas = Empresa::where('nombre', 'like', '%' . $query . '%')
            ->whereHas('proyectos', function($q) use ($carrera_id, $periodo_id) {
                $q->where('periodo_id', $periodo_id)
                ->whereHas('estudiantes', function($sq) use ($carrera_id) {
                    $sq->where('carrera_id', $carrera_id);
                });
            })
            ->limit(10)
            ->pluck('nombre');

        return response()->json($empresas);
    }

    public function sugerenciasAsesor(Request $request)
{
    $query = $request->input('query');
    $carrera_id = session('carrera_id');
    
    if (!$carrera_id) {
        return response()->json([]);
    }

    // Como necesitamos nombre completo, usamos pluck con CONCAT
    $asesores = Asesor::whereHas('carreras', function($q) use ($carrera_id) {
            $q->where('carrera_id', $carrera_id);
        })
        ->where(function($q) use ($query) {
            $q->where('nombre', 'like', '%' . $query . '%')
              ->orWhere('apellido_paterno', 'like', '%' . $query . '%')
              ->orWhere('apellido_materno', 'like', '%' . $query . '%');
        })
        ->limit(10)
        ->pluck(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre_completo"));

    return response()->json($asesores);
}

    public function sugerenciasProyecto(Request $request)
    {
        $query = $request->input('query');
        $carrera_id = session('carrera_id');
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        
        if (!$carrera_id) {
            return response()->json([]);
        }

        $proyectos = Proyecto::where('periodo_id', $periodo_id)
            ->where('nombre', 'like', '%' . $query . '%')
            ->whereHas('estudiantes', function($q) use ($carrera_id) {
                $q->where('carrera_id', $carrera_id);
            })
            ->limit(10)
            ->pluck('nombre');

        return response()->json($proyectos);
    }
    



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProyectoRequest $request)
{
    
    DB::beginTransaction();
    
    try {
        // Manejar empresa
        $empresaId = $request->empresa_id;
        
        // Si es nueva empresa (valor -1)
        if ($empresaId == -1) {
            echo 'creando nueva empresa';
            if (empty($request->nombre_e)) {
                throw new \Exception('El nombre de la empresa es requerido');
            }
            
            // Crear la empresa
            $empresa = Empresa::create([
                'nombre' => $request->nombre_e,
                'giro' => $request->giro,
                'rfc' => $request->rfc,
                'direccion' => $request->direccion,
                'numero' => $request->numero,
                'codigo_postal' => $request->codigo_postal,
                'ciudad' => $request->ciudad,
                'estado' => $request->estado,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'titular' => $request->titular,
                'puesto_titular' => $request->puesto_titular,
                'informacion' => $request->informacion_e,
            ]);
            
            $empresaId = $empresa->id;
           
        } 
        $proyecto = Proyecto::create([
            'num_registro' => $request->num_registro,
            'nombre' => $request->nombre,
            'objetivo_general' => $request->objetivo_general,
            'lugar' => $request->lugar,
            'informacion' => $request->informacion,
            'justificacion' => $request->justificacion,
            'origen' => $request->origen,
            'asesor_id' => $request->asesor_id,
            'empresa_id' => $empresaId,
            'periodo_id' => ConfiguracionServiceProvider::get('periodo_id'),
        ]);
        if (!Auth::check()) {
        return redirect()->route('Inicio_Sesion');
        }
        //Asignar proyecto al estudiante
        $estudiante = Auth::user()->usa;
        $estudiante->proyecto_id = $proyecto->id;
        $estudiante->save();
        //  Procesar asesor externo (si se proporcionó)
        if ($request->filled('correo_ae') && $request->filled('nombre_ae')) {
            // Buscar o crear asesor externo
            $ae = Externo::firstOrCreate(
                ['correo_electronico' => $request->correo_ae],
                [
                    'titulo' => $request->titulo_ae,
                    'nombre' => $request->nombre_ae,
                    'apellido_paterno' => $request->apellido_paterno_ae,
                    'apellido_materno' => $request->apellido_materno_ae,
                    'puesto' => $request->puesto_ae
                ]
            );
    
            // Crear usuario para el asesor externo
            Usuario::firstOrCreate(
                ['nombre_usuario' => $ae->correo_electronico],
                [
                    'usa_id' => $ae->id,
                    'usa_type' => get_class($ae),
                    'contraseña' => Hash::make($ae->correo_electronico),
                ]
            );
            
            // Asignar asesor externo al proyecto
            $proyecto->externo_id = $ae->id;
            $proyecto->save();
            
            
        }      
        DB::commit();
        DocumentoAutomaticoService::guardarSolicitud($estudiante);
        DocumentoAutomaticoService::guardarAnteproyecto($estudiante);
        return redirect()->route("home")
            ->with('success', 'Proyecto registrado correctamente');
        
    } catch (\Throwable $th) {
        DB::rollBack();
        return back()
            ->withErrors(['error' => 'Error al guardar: ' . $th->getMessage()])
            ->withInput();
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto)
    {
        //con inertia
        return Inertia::render('proyecto/editar', [
            'proyecto' => $proyecto
        ]);
        //return view('proyecto.editar',compact("proyecto"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProyectoRequest $request, Proyecto $proyecto)
    {
        //Log::channel('debug')->info('checar');

        if (!Gate::allows('update', $proyecto)) {
            return Inertia::render(
                'estudiante/avisos/no-autorizado'
            );
        }

        // Actualizar proyecto
        $proyecto->fill($request->all());

        $proyecto->save();
        //regenerar documentos  automáticos para todos los estudiantes del proyecto
        $proyecto->load('estudiantes');

        foreach ($proyecto->estudiantes as $estudiante) {

            DocumentoAutomaticoService::guardarSolicitud(
                $estudiante
            );

            DocumentoAutomaticoService::guardarAnteproyecto(
                $estudiante
            );
        }

        return redirect()
            ->route("proyectos.mostrar")
            ->with(
                'success',
                'Proyecto actualizado correctamente'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto)
    {
        //ELIMINAR EL PERIODO QUE ME DIGAN
        $proyecto->delete();
        return redirect()->route("home");
    }

    public function create()
    {
        if (!Auth::check()) {
        return redirect()->route('Inicio_Sesion');
        }
        $estudiante = Auth::user()->usa;
        $proyecto = $estudiante->proyecto;

         // Verificar si ya tiene un proyecto
        if (!is_null($proyecto)) {
            
            $proyecto->load([
                'empresa',
                'periodo',
                'actividades.cronogramas' ,
                'asesor',
                'externo',
                'estudiantes'
            ]);
            
            return Inertia::render('proyecto/mostrar', [
                'proyecto' => $proyecto
            ]);
        } 
        
      
        $carrera_id = $estudiante->carrera_id;
        // O si la relación es directa con la tabla pivote 'asesor_carrera'
        $asesores = Asesor::join('asesor_carrera', 'asesores.id', '=', 'asesor_carrera.asesor_id')
        ->where('asesor_carrera.carrera_id', $carrera_id)
        ->select('asesores.*')
        ->get();

        //$asesores = Asesor::all();
        $empresas = Empresa::all();
        $externos = Externo::all();
        $periodo = Periodo::find(ConfiguracionServiceProvider::get('periodo_id'));
        //con inertia
        //return view('proyecto.crear', compact('asesores','empresas','periodo','externos'));
        return Inertia::render('proyecto/crear', [
            'asesores' => $asesores,
            'empresas' => $empresas,
            'periodo' => $periodo,
            'externos' => $externos,
            'tecnologico' => ConfiguracionServiceProvider::get('tecnologico')
        ]);

    }

    public function show(Proyecto $proyecto)
{
    $proyecto->load([
        'empresa',
        'periodo',
        'actividades.cronogramas',
        'asesor',
        'externo',
        'estudiantes'
    ]);

    return Inertia::render('proyecto/mostrar', [
        'proyecto' => $proyecto
    ]);
}

    public function buscar(Request $request) 
    {
        $termino = $request->input('q');
        
        // Obtener el estudiante autenticado
        $estudiante = auth()->user()->usa;
        
        // Verificar que sea estudiante y tenga carrera
        if (!$estudiante instanceof \App\Models\Estudiante || !$estudiante->carrera_id) {
            return response()->json([]);
        }
        
        // Buscar proyectos que:
        // Tengan nombre similar a la búsque y TENGAN ESTUDIANTES DE LA MISMA CARRERA
        $proyectos = Proyecto::where('nombre', 'like', "%{$termino}%")
            ->whereHas('estudiantes', function($query) use ($estudiante) {
                // Buscar estudiantes que tengan la misma carrera que el estudiante actual
                $query->where('carrera_id', $estudiante->carrera_id);
            })
            ->limit(10)
            ->pluck('nombre');
        
        return response()->json($proyectos);
    }

     public function unirse(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'nombre' => 'required|string',
        ]);

        $proyecto = Proyecto::where('id', $request->id)
                            ->where('nombre', $request->nombre)
                            ->first();

        if (!$proyecto) {
            return back()->withErrors(['error' => 'Proyecto no encontrado. Verifica el ID y el nombre.']);
        }

        $usuario = auth()->user();

        // Verificamos que el usuario sea un estudiante
        if (!$usuario->usa instanceof \App\Models\Estudiante) {
            return back()->withErrors(['error' => 'Solo los estudiantes pueden unirse a proyectos.']);
        }

        $estudiante = $usuario->usa;

        if ($estudiante->proyecto_id !== null) {
            return back()->withErrors(['error' => 'Ya estás asignado a un proyecto.']);
        }

        // Asignamos el proyecto al estudiante
        $estudiante->proyecto_id = $proyecto->id;
        $estudiante->save();

        return redirect()->route('proyectos.create')->with('success', 'Te has unido exitosamente al proyecto.');
    }
    
    public function actualizarFueraTiempo(Request $request)
    {
        $request->validate([
            'activo' => 'required|boolean',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $proyecto = Proyecto::findOrFail($request->proyecto_id);
        
        $proyecto->fuera_de_tiempo = $request->activo; // true o false directamente
        $proyecto->save();

        return back()->with('success', 'Configuración actualizada para este proyecto');
    }
}