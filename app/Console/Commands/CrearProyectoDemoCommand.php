<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use App\Models\Proyecto;
use App\Models\Estudiante;
use App\Models\Usuario;
use App\Models\Externo;
use App\Models\Empresa;
use App\Models\Periodo;
use App\Models\Carrera;

class CrearProyectoDemoCommand extends Command
{
    /**
     * Nombre comando
     */
    protected $signature = 'demo:proyecto';

    /**
     * Descripción
     */
    protected $description =
        'Crea estudiante demo con proyecto';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Creando proyecto demo...');

        /*
        |--------------------------------------------------------------------------
        | Limpiar demo anterior
        |--------------------------------------------------------------------------
        */

        Usuario::where(
            'nombre_usuario',
            'estudiante@gmail.com'
        )->delete();

        Estudiante::where(
            'correo_electronico',
            'estudiante@gmail.com'
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | Datos base
        |--------------------------------------------------------------------------
        */

        $carrera = Carrera::first();
//que el periodo sea el id 11
        $periodo = Periodo::find(11);
        

        if (!$carrera) {

            $this->error('No existe carrera.');

            return;
        }

        if (!$periodo) {

            $this->error('No existe periodo.');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear externo
        |--------------------------------------------------------------------------
        */

        $externo = new Externo();

        $externo->titulo = 'Ing.';

        $externo->nombre = 'Carlos';

        $externo->apellido_paterno = 'Martinez';

        $externo->apellido_materno = 'Ruiz';

        $externo->correo_electronico =
            'externo_demo@gmail.com';

        $externo->puesto =
            'Gerente TI';

        $externo->save();

        /*
        |--------------------------------------------------------------------------
        | Empresa random
        |--------------------------------------------------------------------------
        */

        $empresa = Empresa::find(
            rand(2, 4)
        );

        if (!$empresa) {

            $this->error(
                'No existe empresa entre IDs 2 y 4.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear proyecto
        |--------------------------------------------------------------------------
        */

        $proyecto = new Proyecto();

        $proyecto->num_registro =
            'PRUEBA-' . rand(1000,9999);

        $proyecto->nombre =
            'Prueba de pruebas';

        $proyecto->objetivo_general =
            'Objetivo demo';

        $proyecto->lugar =
            'Empresa demo';

        $proyecto->informacion =
            'Información demo';

        $proyecto->justificacion =
            'Justificación demo';

        $proyecto->origen =
            'Propuesta propia';

        // ASESOR 1
        $proyecto->asesor_id = 1;

        // EXTERNO NUEVO
        $proyecto->externo_id =
            $externo->id;

        // EMPRESA RANDOM
        $proyecto->empresa_id =
            $empresa->id;

        $proyecto->periodo_id =
            $periodo->id;

        $proyecto->save();

        /*
        |--------------------------------------------------------------------------
        | Crear estudiante
        |--------------------------------------------------------------------------
        */

        $estudiante = new Estudiante();

        $estudiante->nombre =
            'Estudiante';

        $estudiante->apellido_paterno =
            'Demo';

        $estudiante->apellido_materno =
            'Proyecto';

        $estudiante->correo_electronico =
            'estudiante@gmail.com';

        $estudiante->numero_de_control =
            '22110099';

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

        $estudiante->proyecto_id =
            $proyecto->id;

        $estudiante->save();

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
            'estudiante@gmail.com';

        $usuario->contraseña =
            Hash::make('1234');

        $usuario->save();

        /*
        |--------------------------------------------------------------------------
        | Final
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('================================');

        $this->info('PROYECTO DEMO CREADO');

        $this->info('================================');

        $this->line('Usuario: estudiante@gmail.com');

        $this->line('Password: 1234');

        $this->line('Proyecto: Prueba de pruebas');

        $this->newLine();

        $this->info('Proceso terminado.');
    }
}