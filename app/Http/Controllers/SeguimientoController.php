<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Seguimiento;
use App\Http\Requests\SeguimientoRequest;
use App\Models\Estudiante;
use App\Models\Parcial;
use App\Models\Ultimo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
            return view('estudiante.aviso.no-autorizado', compact('razon'));
        }
        $usuario = Auth::getUser();
        //dd($usuario->usa_type);
        switch ($usuario->usa_type) {
            case 'App\Models\Asesor':
                //dd("es asesor");
                if($consecutivo == 'primer' or $consecutivo == 'segundo'){
                    
                    $segui = Parcial::firstOrCreate(
                        ['estudiante_id' => $estudiante->id,'consecutivo' => $consecutivo ], 
                    );
                    $segui->califico_interno=Carbon::now();
                    $segui->save();

                    return view('seguimientos.parcial.calificar-interno',compact('estudiante','consecutivo','segui'));                    
                }
                
                if($consecutivo == 'ultimo' ){
                    $ultimo = Parcial::firstOrCreate(
                        ['estudiante_id' => $estudiante->id], 
                    );
                    $ultimo->califico_interno=Carbon::now();
                    $ultimo->save();

                    return view('seguimientos.ultimo.calificar-interno',compact('estudiante','consecutivo','ultimo'));

                }
                break;
            
            case 'App\Models\Externo':
                if($consecutivo == 'primer' or $consecutivo == 'segundo'){
                    
                    $segui = Parcial::firstOrCreate(
                        ['estudiante_id' => $estudiante->id, 'consecutivo' => $consecutivo ],
                    );
                    $segui->califico_externo=Carbon::now();
                    $segui->save();

                    return view('seguimientos.parcial.calificar-externo',compact('estudiante','consecutivo','segui'));                    
                }
                if($consecutivo == 'ultimo' ){
                    $ultimo = Parcial::firstOrCreate(
                        ['estudiante_id' => $estudiante->id],
                    );
                    $ultimo->califico_externo=Carbon::now();
                    $ultimo->save();
                    return view('seguimientos.ultimo.calificar-externo',compact('estudiante','consecutivo','ultimo'));
                }
                break;
            
            
            default:
                echo "este usuario no puede crear segumientos";
                break;
        }
        //ahorita solo estamos trabajando con parciales no con ultimo
       // echo "le vamos a hacer su seguimiento $consecutivo al estudiante $estudiante->nombre"; 
    }

    public function calificar(SeguimientoRequest $request, Estudiante $estudiante, $consecutivo)
    {
        $usuario = Auth::getUser();
        $tipo = $usuario->usa_type;
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
                $campos2 = [
                    'comentarios_interno',
                ];
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
                    'fuentes_externo',
                ];
                $campos2 = [
                    'comentarios_externo',
                ];
                break;
            case 'App\Models\Estudiante':
                # code...
                break;
            
            default:
                # code...
                break;
        }

        if($consecutivo=='primer' or $consecutivo=='segundo'){
            $segui = Parcial::firstOrCreate(
                ['estudiante_id' => $estudiante->id, 'consecutivo' => $consecutivo ], 
                ['consecutivo' => $consecutivo ] 
            );
        }else{
            $segui = Ultimo::firstOrCreate(
                ['estudiante_id' => $estudiante->id ], 
            );
        }
            //dd(get_class($segui));
            $suma = 0;
            foreach ($campos as $campo) {
                if($request->has($campo)){
                    $segui->$campo=$request->input($campo);
                    
                    $suma += (int) $request->input($campo);
                }
            }
            foreach ($campos2 as $campo) {
                if($request->has($campo)){
                    $segui->$campo=$request->input($campo);
                }
            }
            $suma = $suma;
            switch ($tipo) {
                case 'App\Models\Asesor':
                    $segui->promedio_interno =  $suma;
                    break;
                case 'App\Models\Externo':
                    $segui->promedio_externo =  $suma;
                    break;
                case 'App\Models\Estudiante':
                    # code...
                    break;                
                default:
                    # code...
                    break;
            }

            $segui->save();
        
        return redirect()->route("home");
    }


    /**
     * Display the specified resource.
     */
    public function show(Seguimiento $seguimiento)
    {
        //
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
