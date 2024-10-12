<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Listar
        $todos = Empresa::all();
        return view('empresa.listar',compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //MOSTRAR FORMULARIO PARA CREAR
        $empresas = Empresa::all();
        return view('empresa.crear',compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmpresaRequest $request)
    {
        //GUARDAR LOS DATOS QUE VIENEN DEL FORMULARIO DE CREAR
        $nuevo = new Empresa;
        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("empresas.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        return view('empresa.editar',compact("empresa"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        //ACTUALIZAR LA BASE DE DATOS CON LOS DATOS QUE VIENEN DEL FORMULARIO DE EDITAR UN PERIODO
        $empresa->fill($request->all());
        $empresa->save();
        return redirect()->route("empresas.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //ELIMINAR EL PERIODO QUE ME DIGAN
        $empresa->delete();
        //cheacar si esta siendo usada
        if($empresa->proyectos->count()==0){

            //no puedes borrar la empresa porque la tiene al menos un proyecto
        }else{

            //borrar la empresa 
        }
        return redirect()->route("empresas.index");
    }
}
