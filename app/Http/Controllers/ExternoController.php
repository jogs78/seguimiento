<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExternoRequest;
use App\Http\Requests\UpdateExternoRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Providers\ConfiguracionServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Externo;

class ExternoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        if ($buscar) {
            $todos = Externo::where(DB::raw("CONCAT('titulo',' ',nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscar . '%')->get();
        } else {
            $todos = Externo::all();
        }

        return view('externo.listar', compact('todos'));
    }

     public function buscarExterno(Request $request)
    {
        $termino = $request->input('term');

        $resultados = Externo::where('titulo', 'like', '%' . $termino . '%')
            ->orWhere('nombre', 'like', '%' . $termino . '%')
            ->orWhere('apellido_paterno', 'like', '%' . $termino . '%')
            ->orWhere('apellido_materno', 'like', '%' . $termino . '%')
            ->select('id', 'titulo', 'nombre', 'apellido_paterno', 'apellido_materno')
            ->limit(10)
            ->get();

        // Devuelve el nombre completo como sugerencia
        $sugerencias = $resultados->map(function ($est) {
            return [
                'id' => $est->id,
                'value' => $est->nombre . ' ' . $est->apellido_paterno . ' ' . $est->apellido_materno,
            ];
        });

        return response()->json($sugerencias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $externos = Externo::all();
        return view('externo.crear',compact('externos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExternoRequest $request)
    {
        $nuevo = new Externo;
        $nuevo->fill($request->all());
        $nuevo->save();
        return redirect()->route("externos.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Externo $externo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Externo $externo)
    {
        return view('externo.editar',compact("externo"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExternoRequest $request, Externo $externo)
    {
        $externo->fill($request->all());
        $externo->save();
        return redirect()->route("externos.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Externo $externo)
    {
        $externo->delete();
        return redirect()->route("externos.index");
    }

    public function crearCuenta(Externo $externo)
    {
        $usr = new Usuario();
        $usr->usa_id=$externo->id;
        $usr->usa_type = get_class($externo);
        $usr->nombre_usuario = $externo->correo_electronico;
        $usr->contraseña = Hash::make($externo->correo_electronico);
        $usr->save();
        return redirect(route('externos.index'));


    }

    public function proyecto()
    {
        $asesor = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');

        $proyectos= $asesor->proyectos($periodo_id)->get();
//        dd(get_class($asesor));
        if(get_class($asesor)=="App\Models\Externo" )
            return view('externo.listar-proyecto',compact('proyectos')); 
        else
            return view('asesor.lista-de-proyecto',compact('proyectos')); 

    }

    
}
