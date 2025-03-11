<?php

namespace App\Policies;

use App\Models\Carrera;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class CarreraPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $actual): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $actual, Carrera $carrera): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $actual): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $actual, Carrera $carrera): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $actual, Carrera $carrera): bool
    {
        if ($actual->usa_type == "App\Models\Externo" ) return false;
        if ($actual->usa_type == "App\Models\Asesor" ) return false;
        if ($actual->usa_type == "App\Models\Estudiante" ) return false;
        if ($actual->usa_type == "App\Models\Coordinador" ) return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $actual, Carrera $carrera): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $actual, Carrera $carrera): bool
    {
        //
    }
}
