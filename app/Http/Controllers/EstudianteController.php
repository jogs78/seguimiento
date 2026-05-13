<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use App\Models\Estudiante;
use App\Models\Carrera;
use App\Models\Usuario;
use App\Models\Proyecto;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Periodo;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Parcial;
use App\Models\Ultimo;
use Inertia\Inertia;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
    
    
     */
    /*
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        if ($buscar) {
            $todos = Estudiante::where(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscar . '%')->get();
        } else {
            $todos = Estudiante::all();
        }

            return view('estudiante.listar', compact('todos')); // vista por defecto
        
    }*/
    
    public function index(Request $request)
{
    $buscar = $request->input('buscar');
    $usuario = Auth::user();
    
    // Determinar la carrera según el rol del usuario
    if ($usuario->usa_type === 'App\\Models\\Coordinador') {
        // Para coordinadores: usar la carrera de la sesión
        $carrera_id = session('carrera_id');
        
        if (!$carrera_id) {
            return redirect()->route('seleccionar.carrera')
                ->with('error', 'Debes seleccionar una carrera primero');
        }
    } elseif ($usuario->usa_type === 'App\\Models\\Estudiante') {
        // Para estudiantes: usar su carrera directamente
        $estudiante = $usuario->usa;
        $carrera_id = $estudiante->carrera_id;
        
        if (!$carrera_id) {
            return redirect()->route('home')->with('error', 'Tu perfil no tiene una carrera asignada');
        }
    } else {
        return redirect()->route('home')->with('error', 'Acceso no autorizado');
    }
    
    // Resto del código igual...
    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
    
    if (!$periodo_id) {
        return redirect()->back()->with('error', 'No hay un período configurado');
    }
    
    $periodoActual = Periodo::find($periodo_id);
    
    $query = Estudiante::select('estudiantes.*')
        ->join('proyectos', 'estudiantes.proyecto_id', '=', 'proyectos.id')
        ->where('proyectos.periodo_id', $periodo_id)
        ->where('estudiantes.carrera_id', $carrera_id);
    
    // Búsqueda
    if ($buscar) {
        $query->where(function($q) use ($buscar) {
            $q->where('estudiantes.nombre', 'like', '%' . $buscar . '%')
              ->orWhere('estudiantes.apellido_paterno', 'like', '%' . $buscar . '%')
              ->orWhere('estudiantes.apellido_materno', 'like', '%' . $buscar . '%')
              ->orWhere(DB::raw("CONCAT(estudiantes.nombre, ' ', estudiantes.apellido_paterno, ' ', estudiantes.apellido_materno)"), 'like', '%' . $buscar . '%');
        });
    }
    
    $query->orderBy('estudiantes.apellido_paterno')
          ->orderBy('estudiantes.apellido_materno')
          ->orderBy('estudiantes.nombre');
    
    $todos = $query->get();
    
    return Inertia::render('estudiante/listar', [
        'todos' => $todos,
        'periodoActual' => $periodoActual,
        'filtroBuscar' => $buscar
    ]);
}

   public function buscarEstudiante(Request $request)
{
    $termino = $request->input('term');
    $carrera_id = session('carrera_id');
    
    // Obtener el período actual
    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
    
    // Consulta con filtro de período actual (unir con proyectos)
    $resultados = Estudiante::select('estudiantes.id', 'estudiantes.nombre', 'estudiantes.apellido_paterno', 'estudiantes.apellido_materno')
        ->join('proyectos', 'estudiantes.proyecto_id', '=', 'proyectos.id')
        ->where('proyectos.periodo_id', $periodo_id)  // ← FILTRO DE PERÍODO ACTUAL
        ->where('estudiantes.carrera_id', $carrera_id)
        ->where(function($q) use ($termino) {
            $q->where('estudiantes.nombre', 'like', '%' . $termino . '%')
              ->orWhere('estudiantes.apellido_paterno', 'like', '%' . $termino . '%')
              ->orWhere('estudiantes.apellido_materno', 'like', '%' . $termino . '%');
        })
        ->limit(10)
        ->get();
    
    // Devuelve el nombre completo como sugerencia
    $sugerencias = $resultados->map(function ($est) {
        return [
            'id' => $est->id,
            'value' =>  $est->nombre . ' ' . $est->apellido_paterno .
             ' ' . $est->apellido_materno,
        ];
    });
    
    return response()->json($sugerencias);
}



    /**
     * Show the form for creating a new resource.
     */

     public function create()
    {
        //MOSTRAR FORMULARIO PARA CREAR
        $carreras = Carrera::all();
      //return view('estudiante.crear',compact('carreras'));
       return Inertia::render('estudiante/crear', [
        'carreras' => $carreras,
         'auth' => auth()->check(), // Pasar estado de autenticación
        'user' => auth()->user(),   // Pasar usuario si está autenticado
    ]);
        
    }
    /*
   

    public function create()
    {
        //MOSTRAR FORMULARIO PARA CREAR
        $carreras = Carrera::all();
        return view('estudiante.crear',compact('carreras'));
        
    }
*/
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstudianteRequest $request)
    {
        DB::beginTransaction();
        try {
            $nuevo = new Estudiante;
            $nuevo->fill($request->all());    
            $nuevo->save();
            $usr = new Usuario();
            $usr->usa_id=$nuevo->id;
            $usr->usa_type = get_class($nuevo);
            $usr->nombre_usuario = $nuevo->correo_electronico;
            $usr->contraseña = Hash::make($request->contraseña);
            $usr->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        

        return redirect()->route("estudiantes.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        dump(estudiante);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        //MOSTRAR EL FORMULARIO PARA EDITAR UN ESTUDIANTE
        if( ! Gate::allows('update',$estudiante)){
            //con inertia
            return Inertia::render('estudiante/avisos/no-autorizado');
            //return view('estudiante.aviso.no-autorizado');
        }

        //return view('estudiante.editar',compact("estudiante"));
        //con inertia
        return Inertia::render('estudiante/editar', [
            'estudiante' => $estudiante
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstudianteRequest $request, Estudiante $estudiante)
    {
    
        if( ! Gate::allows('update',$estudiante)){
            //con inertia
            return Inertia::render('estudiante/avisos/no-autorizado');
            //return view('estudiante.aviso.no-autorizado');
        }
        //ACTUALIZAR LA BASE DE DATOS CON LOS DATOS QUE VIENEN DEL FORMULARIO DE EDITAR UN PERIODO
        $estudiante->fill($request->all());
        $estudiante->save();
        
        $usuario = Auth::getUser();
        $tipo = $usuario->usa_type;
        switch ($tipo) {
        
        case 'App\Models\Coordinador':
            return redirect()->route("estudiantes.index");
        break;
    }
    return redirect()->route("home");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        if ($estudiante->parciales()->exists()) {
        return redirect()
            ->back()
            ->with('error', 'El estudiante aún está siendo evaluado, así que no se le puede eliminar');
    }

    $estudiante->delete();
    return redirect()
        ->back()
        ->with('success', 'Estudiante Borrado correctamente');

    }

   public function promedio()
{
    $estudiante = Auth::getUser()->usa;
    $primer = $estudiante->primer;
    $segundo = $estudiante->segundo;
    $ultimo = $estudiante->ultimo;
    
    
    $proyecto = $estudiante->proyecto;
    
    if ($proyecto) {
        // Cargar las relaciones anidadas
        $proyecto->load([
            'asesor',
            'externo',
            'estudiantes.carrera.coordinador'  // estudiantes → carrera → coordinador
        ]);
    }

    return Inertia::render('estudiante/promedio', [
        'primer' => $primer,
        'segundo' => $segundo,
        'ultimo' => $ultimo,
        'proyecto' => $proyecto
    ]);
}

    public function mostrar($pagina)
    {
        // Logica para determinar qué vista devolver
        if ($pagina == 'alta-proyecto') {
            return view('estudiante.alta-proyecto');
        } 
        elseif ($pagina == 'fuera-periodo') {
            //con inertia            
            return Inertia::render('asesor/avisos/fuera-periodo');
            
        }
        elseif ($pagina == 'no-calificacion') {
            //con inertia
            return Inertia::render('asesor/avisos/no-calificacion');
           
        }
        elseif ($pagina == 'calificacion') {
            //con inertia
             return Inertia::render('asesor/calificacion');
            
            
        }
        elseif ($pagina == 'no-promedio') {
            //con inertia
             return Inertia::render('estudiante/avisos/no-promedio');
            //return view('estudiante.avisos.no-promedio');
          
        }
        elseif ($pagina == 'si-promedio') {
            return view('estudiante.avisos.si-promedio');
        }
        else {
            return abort(404); // Si la página no existe, lanzamos un 404
        }
    }


    public function solicitud()
    {   
        $estudiante = Auth::getUser()->usa;
        if (!$estudiante || !$estudiante->proyecto) {
        return back()->withErrors(['error' => 'No tienes un proyecto asignado.']);
        }
        $jefe = ConfiguracionServiceProvider::get('jefe_division');
        $numeroControl = Auth::user()->numero_de_control; 
        $cantidadEstudiantes = $estudiante->proyecto->estudiantes()->count();
        $pdf = Pdf::loadview('estudiante.impresiones.solicitud',compact('jefe','estudiante','cantidadEstudiantes')); 
        $nombreArchivo = 'Solicitud ' . $estudiante->numero_de_control . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    public function anteproyecto()
    {
        $estudiante = Auth::getUser()->usa;
        if (!$estudiante || !$estudiante->proyecto) 
        {
        return redirect()->route('home');
        }
       // $jefe = ConfiguracionServiceProvider::get('jefe_division');
        $pdf = Pdf::loadview('estudiante.impresiones.anteproyecto',compact('estudiante')); 
        return $pdf->download('Anteproyecto ' . $estudiante->numero_de_control . '.pdf');
        //return view('estudiante.impresiones.anteproyecto'); 
    }

    public function primer(Estudiante $estudiante)
    {
        if (is_null($estudiante->id)){
            $estudiante = Auth::getUser()->usa;
        }
        //tendriamos que saber 
        //si es un estudiante
        //$externo = Auth::getUser()->usa;
        $primer = $estudiante->primer;
        $pdf = Pdf::loadview('estudiante.impresiones.seguimientos.primer',compact('estudiante','primer')); 
        return $pdf->download('Primer_Seguimiento ' . $estudiante->numero_de_control .'.pdf');
    }

    public function segundo(Estudiante $estudiante)
    {
        if (is_null($estudiante->id)){
            $estudiante = Auth::getUser()->usa;
        }
        $segundo = $estudiante->segundo;
        $pdf = Pdf::loadview('estudiante.impresiones.seguimientos.segundo',compact('estudiante','segundo')); 
        return $pdf->download('Segundo_Seguimiento ' . $estudiante->numero_de_control . '.pdf');      
    }

    public function ultimo(Estudiante $estudiante)
    {
        if (is_null($estudiante->id)){
            $estudiante = Auth::getUser()->usa;
        }
        $ultimo = $estudiante->ultimo;
        $pdf = Pdf::loadview('estudiante.impresiones.seguimientos.ultimo',compact('estudiante','ultimo')); 
        return $pdf->download('Ultimo_Seguimiento ' . $estudiante->numero_de_control .'.pdf');
        
    }

    public function pdf(Request $request)
    {
        // Validar que el archivo sea un PDF
        $request->validate([
            'archivo' => 'required|mimes:pdf|max:2048',  // Máximo tamaño 2MB
        ]);

        // Guardar el archivo en una carpeta dentro de 'storage'
        if ($request->file('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time().'_'.$archivo->getClientOriginalName(); // Nombre único
            $ruta = $archivo->storeAs('pdfs', $nombreArchivo, 'public'); // Guardar en storage/app/public/pdfs

            return back()->with('success', 'Archivo subido exitosamente.')->with('ruta', $ruta);
        }

        return back()->with('error', 'Hubo un problema al subir el archivo.');
    }
    
    
}
