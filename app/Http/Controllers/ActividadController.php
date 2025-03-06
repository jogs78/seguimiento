<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Proyecto;
use App\Models\Periodo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreActividadRequest;
use App\Http\Requests\UpdateActividadRequest;
use Illuminate\Support\Facades\Gate;


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
        //MOSTRAR FORMULARIO PARA CREAR
        $actividades = Actividad::all();
        return view('actividad.crear',compact('actividades','proyecto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Proyecto $proyecto)
    {
        //guardar las activides
        $nueva = new Actividad;
        $nueva->fill($request->all());
        $nueva->proyecto_id = $proyecto->id;
        $nueva->save();
        return redirect(route('proyectos.create'));
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
    public function edit(Proyecto $proyecto, Actividad $actividad)
    {   
        $actividad = $proyecto->actividades->first();
        return view('actividad.editar',compact("actividad","proyecto"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto, Actividad $actividad)
    {
        $proyecto = Proyecto::with('actividades')->find($proyecto->id);
        $validated = $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'semanas' => 'required',
            'orden' => 'required',

        ]);

        $actividad->fill($validated);
        $actividad->save();
        return redirect()->route("proyectos.actividades.index",$proyecto->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto, Actividad $actividad)
    {   
        $proyecto = Proyecto::findOrFail($proyectoId);
        $actividad = $proyecto->actividad()->findOrFail($actividadId);

    if (!$actividad) {
        abort(404, 'La actividad no se encontró o no pertenece a este proyecto.');
    }
        $actividad->delete();
        dd($actividad);
        return redirect()->route("proyectos.actividades.index",$proyecto->id);
    }
}
