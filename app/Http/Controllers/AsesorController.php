<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Proyecto;
use App\Models\Coordinador;
use App\Http\Requests\StoreAsesorRequest;
use App\Http\Requests\UpdateAsesorRequest;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $carrera_id = session('carrera_id'); // Obtener carrera de la sesión
        
        // Si no hay carrera seleccionada, redirigir o mostrar mensaje
        if (!$carrera_id) {
            return redirect()->route('seleccionar.carrera')
                ->with('error', 'Debes seleccionar una carrera primero');
        }
        
        $query = Asesor::whereHas('carreras', function($q) use ($carrera_id) {
            $q->where('carrera_id', $carrera_id); // Solo asesores de esta carrera
        });
        
        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscar . '%')
                ->orWhere('nombre', 'like', '%' . $buscar . '%')
                ->orWhere('apellido_paterno', 'like', '%' . $buscar . '%')
                ->orWhere('apellido_materno', 'like', '%' . $buscar . '%');
            });
        }
        
        $todos = $query->get();
        
        return view('asesor.listar', compact('todos'));
    }

    public function buscarAsesor(Request $request)
    {
        $termino = $request->input('term');
        $carrera_id = session('carrera_id');
        
        if (!$carrera_id) {
            return response()->json([]);
        }
        
        $resultados = Asesor::whereHas('carreras', function($q) use ($carrera_id) {
                $q->where('carrera_id', $carrera_id); // ← FILTRO POR CARRERA EN LA TABLA PIVOTE
            })
            ->where(function($q) use ($termino) {
                $q->where('nombre', 'like', '%' . $termino . '%')
                ->orWhere('apellido_paterno', 'like', '%' . $termino . '%')
                ->orWhere('apellido_materno', 'like', '%' . $termino . '%');
            })
            ->select('id', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->limit(10)
            ->get();

        // Formatear resultados para autocompletado
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
        $proyectos= $asesor->proyectos($periodo_id)->get();
        $coordinador = Coordinador::all();

//        dd(get_class($asesor));
        if(get_class($asesor)=="App\Models\Externo" )
            return view('externo.listar-proyecto',compact('proyectos','coordinador')); 
        else
            return view('asesor.listar-proyecto',compact('proyectos','coordinador')); 

    }

}
