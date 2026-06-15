<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Seguimiento;
use App\Http\Requests\SeguimientoRequest;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Estudiante;
use App\Models\Parcial;
use App\Models\Ultimo;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SeguimientoController extends Controller
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
    public function create(Estudiante $estudiante,$consecutivo)
    {

        $decision = Gate::inspect('calificar', [Seguimiento::class, $estudiante->proyecto, $consecutivo, $estudiante->id ]);

        
        Log::channel('debug')->info('checar');
        if( ! $decision->allowed() ){
            $razon = $decision->message();
            //con inertia
            //return view('estudiante.aviso.no-autorizado', compact('razon'));
            return Inertia::render('estudiante/avisos/no-autorizado', compact('razon'));
        }
        $usuario = Auth::getUser();
        //dd($usuario->usa_type);
        switch ($usuario->usa_type) {
            case 'App\Models\Asesor':
                //dd("es asesor");
                if($consecutivo == 'primer' or $consecutivo == 'segundo'){
                    
                    $segui = Parcial::firstOrCreate(
                        ['estudiante_id' => $estudiante->id, 'consecutivo' => $consecutivo],
                    );
                    $segui->califico_interno = Carbon::now();
                    $segui->save();

                    //dump($segui->toArray());

                    return Inertia::render('seguimientos/parcial/calificar-interno', [
                        'estudiante' => $estudiante->load(['proyecto.periodo', 'carrera']),
                        'consecutivo' => $consecutivo,
                        'segui' => $segui
                    ]);    
                
                }
                
                if($consecutivo == 'ultimo' ){
                    $ultimo = Ultimo::firstOrCreate(
                        ['estudiante_id' => $estudiante->id], 
                    );
                    $ultimo->created_at=Carbon::now();
                    $ultimo->save();
                    
                    //dump($ultimo->toArray());

                    //con inertia
                    return Inertia::render('seguimientos/ultimo/calificar-interno', [
                        'estudiante' => $estudiante->load(['proyecto.periodo', 'carrera']),
                        'consecutivo' => $consecutivo,
                        'ultimo' => $ultimo
                    ]);
                
                }
                break;
            
            case 'App\Models\Externo': 
                if($consecutivo == 'primer' or $consecutivo == 'segundo'){
                    
                    $segui = Parcial::firstOrCreate(
                        ['estudiante_id' => $estudiante->id, 'consecutivo' => $consecutivo ],
                    );
                    $segui->califico_externo=Carbon::now();
                    $segui->save();

                    return Inertia::render('seguimientos/parcial/calificar-externo', [
                        'estudiante' => $estudiante->load(['proyecto.periodo', 'carrera']),
                        'consecutivo' => $consecutivo,
                        'segui' => $segui
                    ]);          
                }
                if($consecutivo == 'ultimo' ){
                    $ultimo = Ultimo::firstOrCreate(
                        ['estudiante_id' => $estudiante->id],
                    );
                    $ultimo->created_at=Carbon::now();
                    $ultimo->save();
                    //con inertia
                    return Inertia::render('seguimientos/ultimo/calificar-externo', [
                        'estudiante' => $estudiante->load(['proyecto.periodo', 'carrera']),
                        'consecutivo' => $consecutivo,
                        'ultimo' => $ultimo
                    ]);
                   
                }
                break;
            
            
            default:
                echo "este usuario no puede crear segumientos";
                break;
        }
    }

    public function calificar(SeguimientoRequest $request, Estudiante $estudiante, $consecutivo)
{
    $usuario = Auth::getUser();
    $tipo = $usuario->usa_type;

    // Configuracion interno
    $internoConfig = Configuracion::where('variable', 'interno')
        ->where('carrera_id', $estudiante->carrera_id)
        ->first();

    $internoActivo = $internoConfig && $internoConfig->valor === 'si';

    switch ($tipo) {

        case 'App\Models\Asesor':

            $campos = [
                'puntualidad_interno',
                'conocimiento_interno',
                'equipo_interno',
                'dedicado_interno',
                'orden_interno',
                'mejoras_interno',

                'portada_interno',
                'agradecimientos_interno',
                'resumen_interno',
                'indice_interno',
                'introduccion_interno',
                'problemas_interno',
                'objetivos_interno',
                'justificacion_interno',
                'marco_teorico_interno',
                'procedimiento_interno',
                'resultados_interno',
                'conclusiones_interno',
                'competencias_interno',
                'fuentes_interno'
            ];

            $campos2 = ['comentarios_interno'];

        break;


        case 'App\Models\Externo':

            $campos = [
                'puntualidad_externo',
                'equipo_externo',
                'iniciativa_externo',
                'mejoras_externo',
                'objetivos_externo',
                'orden_externo',
                'liderazgo_externo',
                'conocimiento_externo',
                'etico_externo',

                'portada_externo',
                'agradecimientos_externo',
                'resumen_externo',
                'indice_externo',
                'introduccion_externo',
                'problemas_externo',
                'justificacion_externo',
                'marco_teorico_externo',
                'procedimiento_externo',
                'resultados_externo',
                'conclusiones_externo',
                'competencias_externo',
                'fuentes_externo'
            ];

            $campos2 = ['comentarios_externo'];

        break;
    }

    if ($consecutivo == 'primer' || $consecutivo == 'segundo') {
        $segui = Parcial::firstOrCreate([
            'estudiante_id' => $estudiante->id,
            'consecutivo' => $consecutivo
        ]);

    } else {
        $segui = Ultimo::firstOrCreate([
            'estudiante_id' => $estudiante->id
        ]);
    }

    $suma = 0;

    foreach ($campos as $campo) {
        if ($request->has($campo)) {
            $segui->$campo = $request->input($campo);
            $suma += (int) $request->input($campo);
        }
    }

    foreach ($campos2 as $campo) {
        if ($request->has($campo)) {
            $segui->$campo = $request->input($campo);
        }
    }

    if ($tipo == 'App\Models\Asesor') {

        $segui->promedio_interno = $suma;

    } elseif ($tipo == 'App\Models\Externo') {

        $segui->promedio_externo = $suma;
    }

    //VERIFICAR SI ES PROYECTO INTERNO
    $esProyectoInterno = false;
    $proyecto = $estudiante->proyecto;

    if ($proyecto && $proyecto->empresa) {

        $empresa = $proyecto->empresa;
        $tecnologico = ConfiguracionServiceProvider::get('tecnologico');
        $normalizar = function ($texto) {
            $texto = mb_strtolower($texto ?? '', 'UTF-8');
            $texto = str_replace(
                ['á','é','í','ó','ú'],
                ['a','e','i','o','u'],
                $texto
            );
            return trim($texto);
        };


        $nombreEmpresa = $normalizar($empresa->nombre);
        $esProyectoInterno =
            $nombreEmpresa === $normalizar($tecnologico)
            || $nombreEmpresa === $normalizar('Instituto Tecnológico de Tuxtla Gutiérrez')
            || $nombreEmpresa === $normalizar('Tecnológico de Tuxtla Gutiérrez')
            || $empresa->rfc === 'TNM140723GFA';
    }
    // DELEGACION AUTOMATICA PARA PROYECTOS INTERNOS
    if ($internoActivo && $esProyectoInterno) {

        if ($consecutivo != 'primer' && $consecutivo != 'segundo') {
            $segui->promedio_externo = $segui->promedio_interno;
            $segui->comentarios_externo ='Proyecto interno - No se requisita esta sección.';
        } else {

            $segui->promedio_externo = $segui->promedio_interno;
            $segui->califico_externo = now();
            $segui->comentarios_externo ='Proyecto interno - No se requisita esta sección.';
        }
    }

    $segui->save();

    return redirect()->route('home');
}


    /**
     * Display the specified resource.
     */
    public function show(Seguimiento $seguimiento)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSeguimientoRequest $request, Estudiante $estudiante, $consecutivo)
    {
        //encontrar ese parcial
        $segui = Parcial::where('estudiante_id', $estudiante->id)
                        ->where('consecutivo',$consecutivo)
                        ->first();

        $segui->promedio_parcial = $request->promedio;
        //guardar el archivo
        $segui->save(); 
                                            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seguimiento $seguimiento)
    {
        //
    }
}
