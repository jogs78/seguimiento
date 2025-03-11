<?php

namespace App\Policies;

use App\Models\Externo;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class ExternoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario, Externo $externo): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" ) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario, Externo $externo): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante" ) return true;
        if ($actual->usa_type == "App\Models\Coordiandor" ) return true;
        return false;
        
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, Externo $externo): bool
    {
        if ($actual->usa_type == "App\Models\Coordiandor" ) return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, Externo $externo): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, Externo $externo): bool
    {
        //
    }
}
