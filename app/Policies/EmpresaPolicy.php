<?php

namespace App\Policies;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class EmpresaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $actual): bool
    {
        if ($actual->usa_type == "App\Models\Coordinador"  ) return true;
        if ($actual->usa_type == "App\Models\Externo"  ) return true;
        
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $actual, Empresa $empresa): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $actual): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $actual, Empresa $empresa): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $actual, Empresa $empresa): bool
    {
        if ($actual->usa_type == "App\Models\Estudiante"  ) return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $actual, Empresa $empresa): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $actual, Empresa $empresa): bool
    {
        //
    }
}
