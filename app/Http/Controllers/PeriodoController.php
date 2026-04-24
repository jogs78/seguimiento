<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodoRequest;
use App\Http\Requests\UpdatePeriodoRequest;
use App\Models\Configuracion;
use App\Models\Periodo;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /*public function index()
    {
        //LISTAR
        $periodos = Periodo::all();
        $actual = ConfiguracionServiceProvider::get('periodo_id');
        $configuracion = Configuracion::where('variable','periodo_id')->first();
        return view('coordinador.periodo.listar',compact('periodos','actual','configuracion')); // return view('coordinador.periodo.listar', ['todos' => $todos]);
    }*/

    public function index()
{
    // LISTAR
    $periodos = Periodo::all();
    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
    
    // Envía SOLO el ID, no el objeto completo
    $actual = $periodo_id;  // ← Esto es un número o null
    
    $configuracion = Configuracion::where('variable', 'periodo_id')->first();
    
    return Inertia::render('coordinador/periodo/listar', [
        'periodos' => $periodos,
        'actual' => $actual,  // ← Ahora es un número
        'configuracion' => $configuracion
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //MOSTRAR FORMULARIO PARA CREAR
        //return view('coordinador.periodo.crear');
        //con inertia
        return Inertia::render('coordinador/periodo/crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePeriodoRequest $request)
    {
        //GUARDAR LOS DATOS QUE VIENEN DEL FORMULARIO DE CREAR
        $nuevo = new Periodo;
        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("periodos.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Periodo $periodo)
    {
        //MOSTRAR UN PERIODO EN ESPECIFICO
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periodo $periodo)
    {
        //MOSTRAR EL FORMULARIO PARA EDITAR UN PERIODO
       // $usuario = Auth::user();
        //echo  $usuario->usa->nombre .  "quiere modificar el periodo";
        return view('coordinador.periodo.editar',compact("periodo"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePeriodoRequest $request, Periodo $periodo)
    {
        //ACTUALIZAR LA BASE DE DATOS CON LOS DATOS QUE VIENEN DEL FORMULARIO DE EDITAR UN PERIODO
        $periodo->fill($request->all());
        $periodo->save();
        return redirect()->route("periodos.index"); //""

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periodo $periodo)
    {
              try {
        $periodo->delete();

        return redirect()
            ->back()
            ->with('success', 'Periodo eliminado correctamente');

    } catch (\Exception $e) {

        // Cualquier otro error general
        return redirect()
            ->back()
            ->with('error', 'Error al eliminar el periodo');
    }

    }
}
