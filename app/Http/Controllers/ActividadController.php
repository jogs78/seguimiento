<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Proyecto;
use App\Models\Periodo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreActividadRequest;
use App\Http\Requests\UpdateActividadRequest;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;


class ActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Proyecto $proyecto)
    {
        if (! Gate::allows('update',$proyecto)){
            return view('estudiante.aviso.no-autorizado');

        }
        $todos = $proyecto->actividades;
        return view('proyecto.mostrar',compact('todos','proyecto'));

    }

    /**
     * Show the form for creating a new resource.
     */
      public function create(Proyecto $proyecto)
    {
        // Obtener los órdenes existentes para este proyecto
        $ordenesExistentes = Actividad::where('proyecto_id', $proyecto->id)
            ->pluck('orden')
            ->toArray();
        
        return Inertia::render('actividad/crear', [
            'proyecto' => $proyecto,
            'ordenesExistentes' => $ordenesExistentes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'actividades' => 'required|array|min:1',
            'actividades.*.nombre' => 'required|string|max:255',
            'actividades.*.descripcion' => 'required|string',
            'actividades.*.semanas' => 'required|integer|min:1',
            'actividades.*.orden' => 'required|integer|min:1',
        ]);
        
        // Verificar que no haya órdenes duplicados
        $ordenes = collect($request->actividades)->pluck('orden')->toArray();
        if (count($ordenes) !== count(array_unique($ordenes))) {
            return redirect()->back()->withErrors(['error' => 'No puede haber dos actividades con el mismo orden']);
        }
        
        // Verificar que los órdenes no estén ya ocupados en la BD
        $ordenesExistentes = Actividad::where('proyecto_id', $proyecto->id)
            ->whereIn('orden', $ordenes)
            ->pluck('orden')
            ->toArray();
        
        if (!empty($ordenesExistentes)) {
            return redirect()->back()->withErrors(['error' => 'El orden ' . implode(', ', $ordenesExistentes) . ' ya está ocupado']);
        }
        
        // Crear todas las actividades
        foreach ($request->actividades as $actividadData) {
            Actividad::create([
                'proyecto_id' => $proyecto->id,
                'nombre' => $actividadData['nombre'],
                'descripcion' => $actividadData['descripcion'],
                'semanas' => $actividadData['semanas'],
                'orden' => $actividadData['orden'],
            ]);
        }
        
        return redirect()->route('proyectos.create', $proyecto->id)
            ->with('success', 'Actividades creadas correctamente');
    }
    
    /**
     * Verificar si un orden está disponible
     */
    public function verificarOrden(Request $request, Proyecto $proyecto)
    {
        $orden = $request->input('orden');
        $actividadId = $request->input('actividad_id'); // Para edición, ignorar la propia actividad
        
        $query = Actividad::where('proyecto_id', $proyecto->id)
            ->where('orden', $orden);
        
        if ($actividadId) {
            $query->where('id', '!=', $actividadId);
        }
        
        $existe = $query->exists();
        
        return response()->json(['disponible' => !$existe]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto, Actividad $actividad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto, Actividad $actividad, $actividadId)
    {   
       $actividad = Actividad::find($actividadId);
        return view('actividad.editar',compact("actividad","proyecto"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActividadRequest $request, Proyecto $proyecto, Actividad $actividad, $actividadId)
    {
        $proyecto = Proyecto::with('actividades')->find($proyecto->id);
        $actividad = Actividad::find($actividadId);
        $actividad->fill($request->all());
        $actividad->save();
        return redirect()->route("proyectos.actividades.index",$proyecto->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto, Actividad $actividad, $actividadId)
    {   
        $proyecto = Proyecto::with('actividades')->find($proyecto->id);
        $actividad = Actividad::find($actividadId);
        $actividad->delete();
        return redirect()->route("proyectos.actividades.index",$proyecto->id);
    }
    
}
