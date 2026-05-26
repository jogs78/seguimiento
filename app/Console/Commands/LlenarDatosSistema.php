<?php

namespace App\Console\Commands;
use App\Models\Estudiante;
use App\Models\Asesor;
use App\Models\Proyecto;
use App\Models\Carrera;
use App\Models\Usuario;
use App\Models\Periodo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use Illuminate\Console\Command;


class LlenarDatosSistema extends Command
{
    /**
     * Nombre del comando
     */
    protected $signature = 'app:llenar-datos-sistema';

    /**
     * Descripción
     */
    protected $description = 'Ejecuta seeders y crea un estudiante demo';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Ejecutando seeders...');

        /*
        |--------------------------------------------------------------------------
        | Ejecutar seeders
        |--------------------------------------------------------------------------
        */

        Artisan::call('db:seed');

        $this->info('Seeders ejecutados correctamente.');

        /*
        |--------------------------------------------------------------------------
        | Buscar carrera
        |--------------------------------------------------------------------------
        */

        $carrera = Carrera::first();

        if (!$carrera) {

            $this->error('No existe ninguna carrera.');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear estudiante
        |--------------------------------------------------------------------------
        */

        $estudiante = new Estudiante();

        $estudiante->nombre = 'Carlos';

        $estudiante->apellido_paterno = 'Santana';

        $estudiante->apellido_materno = 'Demo';

        $estudiante->correo_electronico =
            'santana@gmail.com';

        $estudiante->numero_de_control =
            '22110001';

        $estudiante->telefono =
            '5555555555';

        $estudiante->direccion =
            'Dirección demo';

        $estudiante->institucion_seguridad_social =
            'IMSS';

        $estudiante->numero_de_seguridad_social =
            123456789;

        $estudiante->carrera_id =
            $carrera->id;

        // SIN PROYECTO
        $estudiante->proyecto_id = null;

        $estudiante->save();

        $this->info('Estudiante creado.');

        /*
        |--------------------------------------------------------------------------
        | Crear usuario
        |--------------------------------------------------------------------------
        */

        $usuario = new Usuario();

        $usuario->usa_id =
            $estudiante->id;

        $usuario->usa_type =
            Estudiante::class;

        $usuario->nombre_usuario =
            'santana@gmail.com';

        $usuario->contraseña =
            Hash::make('1234');

        $usuario->save();

        $this->info('Usuario creado.');

        /*
        |--------------------------------------------------------------------------
        | Datos finales
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('================================');

        $this->info('USUARIO CREADO');

        $this->info('================================');

        $this->line('Usuario: santana@gmail.com');

        $this->line('Password: 1234');

        $this->newLine();

        $this->info('Proceso terminado.');
    }
}