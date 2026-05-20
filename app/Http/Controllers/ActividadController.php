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

      
        //con inertia
        return Inertia::render('proyecto/mostrar', [
            'todos' => $todos,
            'proyecto' => $proyecto
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
      public function create(Proyecto $proyecto)
    {
        // Obtener los órdenes existentes (pueden ser múltiples)
        $ordenesExistentes = Actividad::where('proyecto_id', $proyecto->id)
            ->get()
            ->flatMap(function($actividad) {
                // Si el orden contiene comas, dividirlo en múltiples órdenes
                if (str_contains($actividad->orden, ',')) {
                    return array_map('trim', explode(',', $actividad->orden));
                }
                return [$actividad->orden];
            })
            ->map(function($orden) {
                return (int) $orden;
            })
            ->unique()
            ->sort()
            ->values()
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
            'actividades.*.orden' => 'required|string', // Cambiado a string
        ]);
        
        // Procesar cada actividad
        foreach ($request->actividades as $actividadData) {
            $orden = $actividadData['orden'];
            
            // Verificar que el formato del orden sea válido
            $ordenes = $this->parseOrden($orden);
            
            if (empty($ordenes)) {
                return redirect()->back()->withErrors(['error' => "El orden '$orden' no es válido"]);
            }
            
            // Verificar que los órdenes no estén ya ocupados
            $ordenesExistentes = Actividad::where('proyecto_id', $proyecto->id)
                ->get()
                ->flatMap(function($act) {
                    return $this->parseOrden($act->orden);
                })
                ->toArray();
            
            $ordenesOcupados = array_intersect($ordenes, $ordenesExistentes);
            
            if (!empty($ordenesOcupados)) {
                return redirect()->back()->withErrors(['error' => "Los órdenes " . implode(', ', $ordenesOcupados) . " ya están ocupados"]);
            }
            
            // Guardar la actividad
            Actividad::create([
                'proyecto_id' => $proyecto->id,
                'nombre' => $actividadData['nombre'],
                'descripcion' => $actividadData['descripcion'],
                'semanas' => $actividadData['semanas'],
                'orden' => $orden,
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
        $actividadId = $request->input('actividad_id');
        
        // Parsear el orden (puede ser múltiple como "4,7")
        $ordenes = $this->parseOrden($orden);
        
        if (empty($ordenes)) {
            return response()->json(['disponible' => false, 'error' => 'Orden inválido']);
        }
        
        // Obtener órdenes ocupados en el proyecto
        $query = Actividad::where('proyecto_id', $proyecto->id);
        
        if ($actividadId) {
            $query->where('id', '!=', $actividadId);
        }
        
        $ordenesOcupados = $query->get()
            ->flatMap(function($act) {
                return $this->parseOrden($act->orden);
            })
            ->toArray();
        
        $ordenesConflictivas = array_intersect($ordenes, $ordenesOcupados);
        $disponible = empty($ordenesConflictivas);
        
        return response()->json([
            'disponible' => $disponible,
            'ordenes_ocupados' => $ordenesOcupados,
            'ordenes_conflictivas' => $ordenesConflictivas
        ]);
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
       return Inertia::render('actividad/editar', [
            'proyecto' => $proyecto,
            'actividad' => $actividad
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto, $actividadId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'semanas' => 'required|integer|min:1',
            'orden' => 'required|string',
        ]);
        
        $actividad = Actividad::findOrFail($actividadId);
        $nuevoOrden = $request->orden;
        
        // Parsear el nuevo orden
        $nuevasOrdenes = $this->parseOrden($nuevoOrden);
        
        if (empty($nuevasOrdenes)) {
            throw ValidationException::withMessages([
                'orden' => "El orden '$nuevoOrden' no es válido"
            ]);
        }
        
        // Obtener todos los órdenes ocupados por otras actividades
        $ordenesOcupados = Actividad::where('proyecto_id', $proyecto->id)
            ->where('id', '!=', $actividad->id)
            ->get()
            ->flatMap(function($act) {
                return $this->parseOrden($act->orden);
            })
            ->toArray();
        
        $ordenesConflictivas = array_intersect($nuevasOrdenes, $ordenesOcupados);
        
        if (!empty($ordenesConflictivas)) {
            throw ValidationException::withMessages([
                'orden' => "Los órdenes " . implode(', ', $ordenesConflictivas) . " ya están ocupados por otras actividades"
            ]);
        }
        
        $actividad->update($request->all());
        
        return redirect()->route("proyectos.actividades.index",$proyecto->id) ->with('success', 'Actividad actualizada correctamente');
   
    }

    /**
     * Parsea un string de órdenes (ej: "4,7" => [4,7])
     */
    private function parseOrden($orden)
    {
        if (empty($orden)) {
            return [];
        }
        
        // Si es solo un número
        if (is_numeric($orden)) {
            return [(int) $orden];
        }
        
        // Si contiene comas, dividir y limpiar
        if (str_contains($orden, ',')) {
            $ordenes = array_map('trim', explode(',', $orden));
            $ordenes = array_filter($ordenes, 'is_numeric');
            return array_map('intval', $ordenes);
        }
        
        return [];
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
