<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Cronograma;
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
  
        if (!Gate::allows('update', $proyecto)) {
            return view('estudiante.aviso.no-autorizado');
        }
        
        $proyecto->load([
            'empresa',
            'periodo',
            'actividades.cronogramas',
            'asesor',
            'externo',
            'estudiantes'
        ]);

        // Cargar actividades con sus cronogramas
        $actividades = $proyecto->actividades()->with('cronogramas')->get();
        
        // Expandir cada actividad por cada cronograma
        $actividadesExpandidas = [];
        
        foreach ($actividades as $actividad) {
            foreach ($actividad->cronogramas as $cronograma) {
                $actividadesExpandidas[] = [
                    'id' => $actividad->id,
                    'nombre' => $actividad->nombre,
                    'descripcion' => $actividad->descripcion,
                    'cronograma_id' => $cronograma->id,
                    'orden' => $cronograma->orden,
                    'semana_inicio' => $cronograma->semana_inicio,
                    'semana_fin' => $cronograma->semana_fin,
                ];
            }
        }
        
        // Ordenar por orden
        usort($actividadesExpandidas, function($a, $b) {
            return $a['orden'] - $b['orden'];
        });
        
        return Inertia::render('proyecto/mostrar', [
            'todos' => $actividadesExpandidas,
            'proyecto' => $proyecto
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Proyecto $proyecto)
    {
        // Obtener órdenes existentes de los cronogramas
        $ordenesExistentes = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
            $q->where('proyecto_id', $proyecto->id);
        })->pluck('orden')->unique()->sort()->values()->toArray();
        
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
            'actividades.*.semana_inicio' => 'required|integer|min:1',
            'actividades.*.semana_fin' => 'required|integer|min:1|gte:actividades.*.semana_inicio',
            'actividades.*.orden' => 'required|integer|min:1',
        ]);
        
        // Verificar órdenes duplicados
        $ordenes = collect($request->actividades)->pluck('orden')->toArray();
        $ordenesDuplicados = array_diff_assoc($ordenes, array_unique($ordenes));
        
        if (!empty($ordenesDuplicados)) {
            return redirect()->back()->withErrors(['error' => 'Los órdenes no pueden repetirse: ' . implode(', ', $ordenesDuplicados)]);
        }
        
        // Verificar que los órdenes no estén ocupados
        $ordenesExistentes = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
            $q->where('proyecto_id', $proyecto->id);
        })->pluck('orden')->toArray();
        
        $ordenesOcupados = array_intersect($ordenes, $ordenesExistentes);
        
        if (!empty($ordenesOcupados)) {
            return redirect()->back()->withErrors(['error' => "Los órdenes " . implode(', ', $ordenesOcupados) . " ya están ocupados"]);
        }
        
        // Guardar actividades y cronogramas
        foreach ($request->actividades as $actividadData) {
            $actividad = Actividad::create([
                'proyecto_id' => $proyecto->id,
                'nombre' => $actividadData['nombre'],
                'descripcion' => $actividadData['descripcion'],
            ]);
            
            Cronograma::create([
                'actividad_id' => $actividad->id,
                'orden' => $actividadData['orden'],
                'semana_inicio' => $actividadData['semana_inicio'],
                'semana_fin' => $actividadData['semana_fin'],
            ]);
        }
        
        return redirect()->route('proyectos.actividades.index', $proyecto->id)
            ->with('success', 'Actividades creadas correctamente');
    }
    
    /**
     * Verificar si un orden está disponible
     */
    public function verificarOrden(Request $request, Proyecto $proyecto)
    {
        $orden = $request->input('orden');
        $cronogramaId = $request->input('cronograma_id');
        
        $query = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
            $q->where('proyecto_id', $proyecto->id);
        });
        
        if ($cronogramaId) {
            $query->where('id', '!=', $cronogramaId);
        }
        
        $ordenOcupado = $query->where('orden', $orden)->exists();
        
        return response()->json([
            'disponible' => !$ordenOcupado,
            'orden' => $orden
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
    public function edit(Proyecto $proyecto, $actividadId)
{   
    $actividad = Actividad::with('cronogramas')->findOrFail($actividadId);
    
    // Obtener el primer cronograma (o el que quieras editar)
    $cronograma = $actividad->cronogramas->first();
    
    // Obtener órdenes ocupados por otras actividades
    $ordenesOcupados = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
        $q->where('proyecto_id', $proyecto->id);
    })->where('actividad_id', '!=', $actividadId)
      ->pluck('orden')
      ->toArray();
    
    return Inertia::render('actividad/editar', [
        'proyecto' => $proyecto,
        'actividad' => $actividad,
        'cronograma' => $cronograma,
        'ordenesOcupados' => $ordenesOcupados
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
            'semana_inicio' => 'required|integer|min:1',
            'semana_fin' => 'required|integer|min:1|gte:semana_inicio',
            'orden' => 'required|integer|min:1',
            'cronograma_id' => 'required|exists:cronogramas,id',
        ]);
        
        $actividad = Actividad::findOrFail($actividadId);
        $cronograma = Cronograma::findOrFail($request->cronograma_id);
        
        // Verificar que el cronograma pertenece a la actividad
        if ($cronograma->actividad_id != $actividad->id) {
            return redirect()->back()->withErrors(['error' => 'El cronograma no pertenece a esta actividad']);
        }
        
        // Verificar que el nuevo orden no esté ocupado por otro cronograma
        $ordenOcupado = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
            $q->where('proyecto_id', $proyecto->id);
        })->where('id', '!=', $cronograma->id)
        ->where('orden', $request->orden)
        ->exists();
        
        if ($ordenOcupado) {
            throw ValidationException::withMessages([
                'orden' => "El orden {$request->orden} ya está ocupado por otra actividad"
            ]);
        }
        
        // Actualizar actividad
        $actividad->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);
        
        // Actualizar cronograma
        $cronograma->update([
            'orden' => $request->orden,
            'semana_inicio' => $request->semana_inicio,
            'semana_fin' => $request->semana_fin,
        ]);
        
        return redirect()->route("proyectos.actividades.index", $proyecto->id)
            ->with('success', 'Actividad actualizada correctamente');
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto, $actividadId)
    {
        $actividad = Actividad::where('proyecto_id', $proyecto->id)
            ->findOrFail($actividadId);
        
        // Eliminar cronogramas asociados (si no usas cascade)
        $actividad->cronogramas()->delete();
        
        // Eliminar actividad
        $actividad->delete();
        
        return redirect()->route("proyectos.actividades.index", $proyecto->id)
            ->with('success', 'Actividad eliminada correctamente');
    }

    /**
 * Show the form for reusing an activity
 */
public function reutilizar(Proyecto $proyecto, $actividadId)
{
    $actividad = Actividad::with('cronogramas')->findOrFail($actividadId);
    
    // Obtener órdenes existentes en el proyecto actual
    $ordenesExistentes = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
        $q->where('proyecto_id', $proyecto->id);
    })->pluck('orden')->unique()->sort()->values()->toArray();
    
    return Inertia::render('actividad/reutilizar', [
        'proyecto' => $proyecto,
        'actividad' => $actividad,
        'ordenesExistentes' => $ordenesExistentes
    ]);
}

/**
 * Store a reused activity
 */
public function storeReutilizar(Request $request, Proyecto $proyecto, $actividadId)
{
    $request->validate([
        'semana_inicio' => 'required|integer|min:1',
        'semana_fin' => 'required|integer|min:1|gte:semana_inicio',
        'orden' => 'required|integer|min:1',
    ]);
    
    $actividadOriginal = Actividad::findOrFail($actividadId);
    
    // Verificar si el orden ya está ocupado
    $ordenOcupado = Cronograma::whereHas('actividad', function($q) use ($proyecto) {
        $q->where('proyecto_id', $proyecto->id);
    })->where('orden', $request->orden)->exists();
    
    if ($ordenOcupado) {
        return redirect()->back()->withErrors(['orden' => "El orden {$request->orden} ya está ocupado"]);
    }
    
    // Crear nueva actividad (copiar la original)
    $nuevaActividad = Actividad::create([
        'proyecto_id' => $proyecto->id,
        'nombre' => $actividadOriginal->nombre,
        'descripcion' => $actividadOriginal->descripcion,
    ]);
    
    // Crear nuevo cronograma
    Cronograma::create([
        'actividad_id' => $nuevaActividad->id,
        'orden' => $request->orden,
        'semana_inicio' => $request->semana_inicio,
        'semana_fin' => $request->semana_fin,
    ]);
    
    return redirect()->route('proyectos.show', $proyecto->id)
        ->with('success', 'Actividad reutilizada correctamente');
}
    
}
