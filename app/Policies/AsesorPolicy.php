<?php

namespace App\Policies;

use App\Models\Asesor;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class AsesorPolicy
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
    public function view(Usuario $actual, Asesor $asesor): bool
    {
        if ($actual->usa_type == "App\Models\Coordinador"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $actual): bool
    {
        if ($actual->usa_type == "App\Models\Coordinador"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $actual, Asesor $asesor): bool
    {
        if ($actual->usa_type == "App\Models\Coordinador"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $actual, Asesor $asesor): bool
    {
        if ($actual->usa_type == "App\Models\Coordinador"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $actual, Asesor $asesor): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $actual, Asesor $asesor): bool
    {
        //
    }
}
