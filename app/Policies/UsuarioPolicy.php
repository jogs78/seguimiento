<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class UsuarioPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Usuario $usuario): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
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
    public function update(User $user, Usuario $usuario): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Usuario $usuario): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Usuario $usuario): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Usuario $usuario): bool
    {
        //
    }
}
