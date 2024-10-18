<?php

namespace App\Http\Controllers;

use App\Models\Parcial;
use App\Http\Requests\StoreParcialRequest;
use App\Http\Requests\UpdateParcialRequest;

class ParcialController extends Controller
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
        return view('seguimientos.parcial');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParcialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Parcial $parcial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parcial $parcial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParcialRequest $request, Parcial $parcial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parcial $parcial)
    {
        //
    }
}
