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


    public function store(Request $request, $proyectoId)
{
    $nombres = $request->input('nombre');
    $descripciones = $request->input('descripcion');
    $semanas = $request->input('semanas');
    $ordenes = $request->input('orden');

    foreach ($nombres as $i => $nombre) {
        // Puedes agregar validación adicional aquí
        Actividad::create([
            'nombre' => $nombre,
            'descripcion' => $descripciones[$i],
            'semanas' => $semanas[$i],
            'orden' => $ordenes[$i],
            'proyecto_id' => $proyectoId,
        ]);
    }

    return redirect()->route('proyectos.create', $proyectoId)
                     ->with('success', 'Actividades guardadas correctamente.');
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
