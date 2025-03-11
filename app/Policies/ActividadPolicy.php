<?php

namespace App\Policies;

use App\Models\Actividad;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class ActividadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $actual): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return true;
        if ($actual->usa_type == "App\Models\Asesor" ) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $actual, Proyecto $proyecto): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" && $proyecto->externo_id == $actual->usa_id ) return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $actual, Proyecto $proyecto): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" && $proyecto->externo_id == $actual->usa_id ) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $actual, Proyecto $proyecto): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" && $proyecto->externo_id == $actual->usa_id ) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $actual, Proyecto $proyecto): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" && $proyecto->externo_id == $actual->usa_id ) return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $actual, Actividad $actividad): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $actual, Actividad $actividad): bool
    {
        //
    }
}
