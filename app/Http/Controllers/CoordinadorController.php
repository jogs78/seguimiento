<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use App\Http\Requests\StoreCoordinadorRequest;
use App\Http\Requests\UpdateCoordinadorRequest;
use App\Models\Proyecto;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Configuracion;
use App\Models\Asesor;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\AsesoresExport;
use App\Exports\ExternosExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;


class CoordinadorController extends Controller
{


    public function tabla(){
        $coordinador = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $proyectos = $coordinador->proyectos( $periodo_id);



//        $proyectos = Proyecto::where('periodo_id', $periodo_id)->get();


        $asesores = Asesor::all();
        return view ('coordinador.tabla', compact('proyectos','asesores'));
    }
    public function asignarAsesor1(){
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');
        $proyectos = Proyecto::where('periodo_id', $periodo_id)->get();
        return view ('coordinador.asignar-asesor1', compact('proyectos'));
    }

    public function asignarAsesor2(Request $request){
        
        $proyecto = Proyecto::find($request->proyecto_id);
        $asesores = Asesor::all();
        return view ('coordinador.asignar-asesor2', compact('proyecto','asesores'));
    }
    public function asignarAsesor3(Request $request, $proyecto_id){
        

        //aqui debemos implementar el chequedo
        Log::channel('debug')->info('checar');
        $proyecto = Proyecto::find($proyecto_id);
        if (! Gate::allows('update',$proyecto)){
            return "NO PUEDE ACTUALIZAR";

        }


        $proyecto = Proyecto::find($proyecto_id);
        $proyecto->asesor_id = $request->asesor_id;
        $proyecto->save();
        return redirect(route('coordinadores.tabla'));
    }

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
    public function store(StoreCoordinadorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Coordinador $coordinador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coordinador $coordinador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoordinadorRequest $request, Coordinador $coordinador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coordinador $coordinador)
    {
        //
    }

    public function periodo()
    {
        $coordinador = Auth::getUser()->usa;
        $periodo_id = ConfiguracionServiceProvider::get('periodo_id');


        return view('coordinador.periodo.listar',compact('periodo_id')); 
    }

    public function estudiante()
    {
        $coordinador = Auth::getUser()->usa;
        $estudiante_id = ConfiguracionServiceProvider::get('estudiante_id');
        return view('coordinador.listar-estudiantes',compact('estudiante_id')); 
    } 

    public function asesores(){
        $coordinador = Auth::getUser()->usa;
        $asesor_id = ConfiguracionServiceProvider::get('asesor_id');
        return view('coordinador.listar-asesores',compact('asesor_id')); 
    }

    public function exportarLista(){
        return Excel::download(new UsersExport, 'Estudiantes.xlsx');
    }

    public function exportandoLista(){
        return Excel::download(new AsesoresExport, 'Asesores_Internos.xlsx');
    }

    public function exportLista(){
        return Excel::download(new ExternosExport, 'Asesores_Externos.xlsx');
    }
}
