<?php

namespace App\Policies;

use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class ProyectoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $actual): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $actual, Proyecto $proyecto): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $actual): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $actual, Proyecto $proyecto): bool
    {
        $usuario_actual = $actual->usa;

        Log::channel('debug')->info("en la politica update el usuario $actual->id al proyecto $proyecto->id");

        Log::channel('debug')->info("$actual->usa_type");

        if($actual->usa_type == "App\Models\Estudiante" && $proyecto->estudiantes->pluck('id')->contains($actual->usa_id)){
            Log::channel('debug')->info("es un estudiante y es su proyecto");
            return true;
        } 

        /*
        if($actual->usa_type == "App\Models\Coordinador" && $usuario_actual->carrera_id == $proyecto->estudiantes[0]->carrera->id ){
            Log::channel('debug')->info("es un Coordiandor y es su proyecto");
            return true;
        } */

        //nuevo por lo que la llave ahora es coordinador_id en la tabla carrera
        $estudiante = $proyecto->estudiantes->first();

        if(
            $actual->usa_type == "App\Models\Coordinador" &&
            $estudiante &&
            $estudiante->carrera->coordinador_id == $actual->usa_id
        ){
            Log::channel('debug')->info("es un coordinador y es su proyecto");
            return true;
        }
        //
        
        Log::channel('debug')->info("regresar falso porque no entro en ninguno");
        return false;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $actual, Proyecto $proyecto): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $actual, Proyecto $proyecto): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $actual, Proyecto $proyecto): bool
    {
        //
    }
}
