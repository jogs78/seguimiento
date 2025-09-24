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
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\Estudiante;


class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $buscarEstudiante = $request->input('buscar');
    $buscarProyecto = $request->input('buscar_proyecto');
    $buscarAsesor = $request->input('buscar_asesor');
    $buscarEmpresa = $request->input('buscar_empresa');
    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');

    $proyectos = Proyecto::with(['empresa', 'asesor', 'externo', 'estudiantes'])
        ->where('periodo_id', $periodo_id)
        ->when($buscarEstudiante, function ($query, $buscarEstudiante) {
            $query->whereHas('estudiantes', function ($q) use ($buscarEstudiante) {
                $q->whereRaw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ["%{$buscarEstudiante}%"]);
            });
        })
        ->when($buscarProyecto, function ($query, $buscarProyecto) {
            $query->where('nombre', 'like', "%{$buscarProyecto}%");
        })
        ->when($buscarAsesor, function ($query, $buscarAsesor) {
            $query->whereHas('asesor', function ($q) use ($buscarAsesor) {
                $q->where('nombre', 'like', "%{$buscarAsesor}%");
            });
        })
        ->when($buscarEmpresa, function ($query, $buscarEmpresa) {
            $query->whereHas('empresa', function ($q) use ($buscarEmpresa) {
                $q->where('nombre', 'like', "%{$buscarEmpresa}%");
            });
        })
        ->get();

    $asesores = Asesor::all();

    return view('coordinador.tabla', compact('proyectos', 'asesores'));

    }

    public function sugerencias(Request $request) //para buscar por el nombre del estudiante
    {
        $query = $request->input('query');

        $estudiantes = Estudiante::whereRaw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) LIKE ?", ["%{$query}%"])
            ->limit(10)
            ->pluck(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre_completo"));

        return response()->json($estudiantes);
    }

    public function sugerenciasEmpresa(Request $request)
    {
        $query = $request->input('query');

        $empresas = Empresa::where('nombre', 'like', '%' . $query . '%')
            ->limit(10)
            ->pluck('nombre');

        return response()->json($empresas);
    }

    public function sugerenciasAsesor(Request $request)
    {
        $query = $request->input('query');

        $asesores = Asesor::where('nombre', 'like', '%' . $query . '%')
        ->limit(10)
        ->pluck('nombre');

        return response()->json($asesores);
    }

    public function sugerenciasProyecto(Request $request)
    {
        $query = $request->input('query');
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');

        $proyectos = Proyecto::where('periodo_id', $periodo_id)
            ->where('nombre', 'like', '%' . $query . '%')
            ->limit(10)
            ->pluck('nombre');

        return response()->json($proyectos);
    }
    



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProyectoRequest $request)
    {
        //esto debe esta en una transaccion
        //GUARDAR LOS DATOS QUE VIENEN DEL FORMULARIO DE CREAR
        //SI empresa_id = -1 es que la empresa no esta dadad de alta 
        DB::beginTransaction();
        try {

            $proyecto = new Proyecto;
//            dd($request->all());
            $datos = $request->all();
            unset($datos['empresa_id']);
            $proyecto->fill($datos);
            $proyecto->save();
    
            if($request->empresa_id == -1 ) {
                $empresa = new Empresa();
                $empresa->nombre = $request->nombre_e;
                $empresa->giro = $request->giro;
                $empresa->rfc = $request->rfc;
                $empresa->direccion = $request->direccion;
                $empresa->telefono = $request->telefono;
                $empresa->correo = $request->correo;
                $empresa->titular = $request->titular;
                $empresa->puesto_titular = $request->puesto_titular;
                $empresa->informacion = $request->informacion_e;
                $empresa->save();
                $proyecto->empresa_id = $empresa->id;
            };      


            $estudiante = Auth::user()->usa;
            $estudiante->proyecto_id = $proyecto->id;
            $estudiante->save();



//            titulo | nombre             | apellido_paterno | apellido_materno | correo_electronico                | puesto

            //CREAR USUARIO PARA EL ASESOR EXTERNO
            $ae = Externo::firstOrCreate(
                ['correo_electronico' => $request->correo_ae],
                ['titulo' => $request->titulo_ae,
                'nombre' => $request->nombre_ae,
                'apellido_paterno' => $request->apellido_paterno_ae,
                'apellido_materno' => $request->apellido_materno_ae,
                'puesto' => $request->puesto_ae ]
            );
            $ae->save();
            $proyecto->externo_id = $ae->id;
            $usr = Usuario::firstOrCreate(
                ['nombre_usuario' => $ae->correo_electronico],

                ['usa_id' => $ae->id,
                'usa_type' => get_class($ae),
                'contraseña' => Hash::make($ae->correo_electronico),    
                ]
            );
            $usr->save();
            $proyecto->externo_id = $ae->id;
            $proyecto->periodo_id = ConfiguracionServiceProvider::get('periodo_id');
            $proyecto->save();

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
            dump($th->getMessage());
            dd($th);

        }

     
     
        return redirect()->route("home");
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto)
    {
        return view('proyecto.editar',compact("proyecto"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProyectoRequest $request, Proyecto $proyecto)
    {
        Log::channel('debug')->info('checar');
        if (! Gate::allows('update',$proyecto)){
            return view('estudiante.aviso.no-autorizado');

        }
        //ACTUALIZAR LA BASE DE DATOS CON LOS DATOS QUE VIENEN DEL FORMULARIO DE EDITAR UN PERIODO
        $proyecto->fill($request->all());
        $proyecto->save();
        return redirect()->route("home");
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

        $estudiante = Auth::user()->usa;
        $proyecto = $estudiante->proyecto;



        //debe verificar si tiene un proyecto ya creado 
        if(! is_null( $proyecto )){
            return view ('proyecto.mostrar',compact('proyecto'));

        } 
        
        //return view (vista que muestra el proyecto y con el enlace de "actividades del proyecto")
        //si no entonces que cargue el registro
        
        $asesores = Asesor::all();
        $empresas = Empresa::all();
        $externos = Externo::all();
        $periodo = Periodo::find(ConfiguracionServiceProvider::get('periodo_id'));
        return view('proyecto.crear', compact('asesores','empresas','periodo','externos'));

    }

    public function buscar(Request $request)
    {
        $termino = $request->input('q');

        $proyectos = Proyecto::where('nombre', 'like', "%{$termino}%")
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

        /** @var \App\Models\Estudiante $estudiante */
        $estudiante = $usuario->usa;

        if ($estudiante->proyecto_id !== null) {
            return back()->withErrors(['error' => 'Ya estás asignado a un proyecto.']);
        }

        // Asignamos el proyecto al estudiante
        $estudiante->proyecto_id = $proyecto->id;
        $estudiante->save();

        return redirect()->route('proyectos.crear')->with('success', 'Te has unido exitosamente al proyecto.');
    }
    
}