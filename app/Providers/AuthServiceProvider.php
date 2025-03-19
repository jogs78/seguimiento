<?php

namespace App\Providers;
use App\Models\Seguimiento;
use App\Policies\SeguimientoPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
//        Seguimiento::class => SeguimientoPolicy::class,
        //Estudiante::class => EstudiantePolicy::class,
    ];
    

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
