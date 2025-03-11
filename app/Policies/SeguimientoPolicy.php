<?php

namespace App\Policies;

use Illuminate\Support\Facades\Log;
use App\Models\Proyecto;
use App\Models\Seguimiento;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class SeguimientoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $actual, Proyecto $proyecto): bool
    {
        if ($actual->usa_type == "App\Models\Estudainte" && $proyecto->externo_id == $actual->usa_id ) return true;
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

    public function calificar(Usuario $actual, Proyecto $proyecto): bool
    {
        Log::channel('debug')->info("Entra en la habilidad de calificar, puede $actual->nombre_usuario($actual->usa_id)  , al proyecto $proyecto->id ($proyecto->externo_id)  ");
        Log::channel('debug')->info("tengo $actual->use_type   , $proyecto->asesor_id  y $actual->usa_id  ");

        if ($actual->usa_type == "App\Models\Asesor"  && $proyecto->asesor_id ==  $actual->usa_id ){
            //falta considerar ¿ya lo hice?
            //estoy en tiempo
            return true;
        }         
        if ($actual->usa_type == "App\Models\Externo" && $proyecto->externo_id == $actual->usa_id ) {

            //falta considerar ¿ya lo hice?
            //estoy en tiempo
            return true;
        }

        
        return false; 
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
