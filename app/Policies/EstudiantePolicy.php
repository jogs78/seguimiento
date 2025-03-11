<?php

namespace App\Policies;

use Illuminate\Support\Facades\Log;
use App\Models\Estudiante;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;


class EstudiantePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estudiante $estudiante): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update( Usuario $actual, Estudiante $estudiante): bool
    {   
        Log::channel('debug')->info("Es un usuario: $actual->usa_type su id de estudiantes es $estudiante->id y su id de usuario es $actual->usa_id");
        //return $actual->usa_id === $estudiante->id;
        if ($actual->usa_type == "App\Models\Estudiante" && $estudiante->id == $actual->usa_id ) {return true;}
        if ($actual->usa_type == "App\Models\Coordinador" ) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Estudiante $estudiante): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Estudiante $estudiante): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Estudiante $estudiante): bool
    {
        //
    }
}

