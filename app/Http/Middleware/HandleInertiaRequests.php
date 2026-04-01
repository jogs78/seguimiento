<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
        ...parent::share($request),
        'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'usa_id' => $request->user()->usa_id,
                    'usa_type' => $request->user()->usa_type,
                    'usa' => $request->user()->usa ? [
                        'nombre' => $request->user()->usa->nombre,
                        'apellido_paterno' => $request->user()->usa->apellido_paterno,
                        'apellido_materno' => $request->user()->usa->apellido_materno,
                    ] : null,
                    'es_jefe_division' => $request->user()->usa && method_exists($request->user()->usa, 'esJefeDivision') 
                    ? $request->user()->usa->esJefeDivision() 
                    : false,
                ] : null,
            ],
        'carrera_actual' => $request->session()->has('carrera_id') ? [
            'id' => $request->session()->get('carrera_id'),
            'nombre' => $request->session()->get('carrera_nombre'), // ← Ahora sí existe
        ] : null,
        'flash' => [
            'errorsesion' => fn () => $request->session()->get('errorsesion'), 
            'errorcontra' => fn () => $request->session()->get('errorcontra'), 
        ],
    ];
    }
}
