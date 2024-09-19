<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodoRequest;
use App\Http\Requests\UpdatePeriodoRequest;
use App\Models\Periodo;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //LISTAR
        $todos = Periodo::all();
        return view('coordinador.periodo.listar',compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //MOSTRAR FORMULARIO PARA CREAR
        return view('coordinador.periodo.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePeriodoRequest $request)
    {
        //GUARDAR LOS DATOS QUE VIENEN DEL FORMULARIO DE CREAR
        $nuevo = new Periodo;
//        dd($request->all());
        $nuevo->fill($request->all());
/*        $nuevo->nombre = $request->nombreperiodo;
        $nuevo->fecha_inicio = $request->fechainicio;
        $nuevo->fecha_final = $request->fechaconclusion;
        $nuevo->fecha_inicio_1er_reporte = $request->fechainicio1;
        $nuevo->fecha_final_1er_reporte = $request->fechafin1;
        $nuevo->fecha_inicio_2do_reporte = $request->fechainicio2;
        $nuevo->fecha_final_2do_reporte = $request->fechafin2;
        $nuevo->fecha_inicio_reporte_final = $request->fechainiciof;
        $nuevo->fecha_final_reporte_final = $request->fechafinf;
*/
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
        return redirect()->route("periodos.index");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periodo $periodo)
    {
        //ELIMINAR EL PERIODO QUE ME DIGAN
        $periodo->delete();
        return redirect()->route("periodos.index");

    }
}