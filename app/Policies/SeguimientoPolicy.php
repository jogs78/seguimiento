<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

use App\Models\Parcial;
use App\Models\Ultimo;
use Illuminate\Support\Facades\Log;
use App\Models\Proyecto;
use App\Models\Seguimiento;
use App\Models\Usuario;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Periodo;
use Carbon\Carbon;

class SeguimientoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $actual, Proyecto $proyecto): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" && $proyecto->externo_id == $actual->usa_id ) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $actual, Seguimiento $seguimiento): bool
    {
        if ($actual->usa_type == "App\Models\Asesor"  && $proyecto->asesor_id ==  $actual->usa_id ){
            return true;
        }         
        if ($actual->usa_type == "App\Models\Externo" && $proyecto->externo_id == $actual->usa_id ) return true;
        return false; 
    }

    public function calificar(Usuario $actual, Proyecto $proyecto, $consecutivo, $aQuien)
    {
        $porRealizar = false;
        $en_tiempo   = false;
        $asignado    = false;
        Log::channel('debug')->info("Entra en la habilidad de calificar, puede $actual->nombre_usuario($actual->usa_id)  , al proyecto $proyecto->id ($proyecto->externo_id)  ");
        Log::channel('debug')->info("tengo $actual->use_type   , $proyecto->asesor_id  y $actual->usa_id  ");
        Log::channel('debug')->info("calificar el $consecutivo para $aQuien");


                
        if ($actual->usa_type == "App\Models\Asesor"  && $proyecto->asesor_id ==  $actual->usa_id) $asignado = true;
        if ($actual->usa_type == "App\Models\Externo" && $proyecto->externo_id == $actual->usa_id) $asignado = true;


        $peridoActual = Periodo::find( ConfiguracionServiceProvider::get('periodo_id') );
        $fechaActual = Carbon::today();

        switch ($consecutivo) {
            case 'primer':
                $inicio = $peridoActual->fecha_inicio_1er_reporte;
                $fin = $peridoActual->fecha_final_1er_reporte;
                break;
            case 'segundo':
                $inicio = $peridoActual->fecha_inicio_2do_reporte;
                $fin = $peridoActual->fecha_final_2do_reporte;
                break;
            case 'ultimo':
                $inicio = $peridoActual->fecha_inicio_reporte_final;
                $fin = $peridoActual->fecha_final_reporte_final;
                break;
            default:
                return false;
        }

        // Verificar si la fecha actual está dentro del intervalo
        if ($fechaActual->between($inicio, $fin)) {
            $en_tiempo = true;
        } else {
            $en_tiempo = false;
        }

        


        if ($actual->usa_type == "App\Models\Asesor" && $proyecto->asesor_id ==  $actual->usa_id){ 
            if($consecutivo == "primer" || $consecutivo == "segundo"){
                $calificacion = Parcial::where('estudiante_id', $aQuien)->where('consecutivo', $consecutivo )->pluck('puntualidad_interno')->first();
            }elseif($consecutivo == "ultimo"){
                $calificacion = Ultimo::where('estudiante_id', $aQuien)->pluck('portada_interno')->first();
            }
            if(is_null($calificacion)){
                $porRealizar = true;
                Log::channel('debug')->info("no ha realizado este seguimiento");   
            } 
        }         
        if ($actual->usa_type == "App\Models\Externo" && $proyecto->externo_id == $actual->usa_id ){
            if($consecutivo == "primer" || $consecutivo == "segundo"){
                $calificacion = Parcial::where('estudiante_id', $aQuien)->where('consecutivo', $consecutivo )->pluck('puntualidad_externo')->first();
            }elseif($consecutivo == "ultimo"){
                $calificacion = Ultimo::where('estudiante_id', $aQuien)->pluck('portada_externo')->first();
            }
            if(is_null($calificacion)){
                $porRealizar = true;
                Log::channel('debug')->info("no ha realizado este seguimiento");   
            } 
        }

        if(!$asignado ){
            return Response::deny("Este estudiante no lo tiene asignado");
        }

        if(!$porRealizar){
            Log::channel('debug')->info(" Deny realizado ");
            return Response::deny("Ya ha realizado este seguimiento");

        }

        if(!$en_tiempo){
            Log::channel('debug')->info(" Deny tiempo ");
            return Response::deny("Esta fuera de tiempo para este seguimiento");
        }

        Log::channel('debug')->info(" -- ALLOW ");
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $actual, Seguimiento $seguimiento): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $actual, Seguimiento $seguimiento): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $actual, Seguimiento $seguimiento): bool
    {
        //
    }
}
