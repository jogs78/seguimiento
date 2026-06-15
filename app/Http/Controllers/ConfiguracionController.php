<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConfiguracionRequest;
use App\Http\Requests\UpdateConfiguracionRequest;
use Illuminate\Http\Request;
use App\Models\Configuracion;
use Inertia\Inertia;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        //devolver todas las configuraciones a la vista con inertia excepto la variable "interno"
        
        $todos = Configuracion::whereNotIn('variable', ['interno'])->get();
        
        return Inertia::render('configuracion/listar', [
            'configuraciones' => $todos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /*
        $configuraciones = Configuracion::all();
        return view('configuracion.crear',compact('configuraciones'));*/
        //con inertia
        $configuraciones = Configuracion::all();
        return Inertia::render('configuracion/crear', [
            'configuraciones' => $configuraciones
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConfiguracionRequest $request)
    {
        $nuevo = new Configuracion;

        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("configuraciones.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracion)
    {
        return Inertia::render('configuracion/editar', [
            'configuracion' => $configuracion
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConfiguracionRequest $request, Configuracion $configuracion)
    {
        $configuracion->fill($request->all());
        $configuracion->save();
        return redirect()->route("configuraciones.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuracion $configuracion)
    {

              try {
        $configuracion->delete();

        return redirect()
            ->back()
            ->with('success', 'Configuracion eliminada correctamente');

    } catch (\Exception $e) {

        // Cualquier otro error general
        return redirect()
            ->back()
            ->with('error', 'Error al tratar de eliminar la configuracion');
    }
    }


    public function cambiarInterno(Request $request)
    {
        $request->validate([
            'valor' => 'required|in:si,no'
        ]);

        $carreraId = session('carrera_id');

        $configuracion = Configuracion::where('variable', 'interno')
            ->where('carrera_id', $carreraId)
            ->first();

        //si no existe, crearla
        if (!$configuracion) {
            $configuracion = new Configuracion();
            $configuracion->variable = 'interno';
            $configuracion->carrera_id = $carreraId;
        }

        if ($configuracion) {
            $configuracion->valor = $request->valor;
            $configuracion->save();
        }

        return back();
    }
}
