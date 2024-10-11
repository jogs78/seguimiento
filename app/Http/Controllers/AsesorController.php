<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Http\Requests\StoreAsesorRequest;
use App\Http\Requests\UpdateAsesorRequest;
use Illuminate\Http\Request;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsesorRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsesorRequest $request, Asesor $asesor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asesor $asesor)
    {
        //
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

}
