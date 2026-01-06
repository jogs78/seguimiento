<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsuarioController extends Controller
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
    public function store(StoreUsuarioRequest $request)
    {
        $nuevo = new Usuario;

        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("home");
    }

    public function cambiarPassword(Request $request)
{
    $request->validate([
        'password_actual' => 'required',
        'password' => 'required|confirmed',
    ]);

    $nuevo = Auth::user();

    // Verificar contraseña actual
    if (!Hash::check($request->password_actual, $nuevo->contraseña)) {
        return back()->withErrors([
            'password_actual' => 'Error con la contraseña Actual',
            'password'   => 'Error con la contraseña Nueva.',
        ]);
    }

    // Guardar nueva contraseña
    $nuevo->contraseña = Hash::make($request->password);
    $nuevo->save();
    return redirect()->back()->with('success', ' ');
    return redirect()->route('home')
        ->with('success', 'Contraseña actualizada correctamente');
}

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
