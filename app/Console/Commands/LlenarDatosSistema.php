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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:llenar-datos-sistema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llena la base de datos con datos relacionados (estudiantes, asesores, proyectos, usuarios)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
         $this->info('Iniciando llenado de datos iniciales...');

        // 1. Llenar primero con los seeders
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        //$periodo = Periodo::factory()->create();

        $hoy = Carbon::now();

        if ($hoy->month <= 6) {

            $nombrePeriodo = 'Enero - Junio ' . $hoy->year;

            $fechaInicio = Carbon::create($hoy->year, 1, 1);

            $fechaFinal = Carbon::create($hoy->year, 6, 30);

        } else {

            $nombrePeriodo = 'Agosto - Diciembre ' . $hoy->year;

            $fechaInicio = Carbon::create($hoy->year, 8, 1);

            $fechaFinal = Carbon::create($hoy->year, 12, 31);
        }

        // Buscar si YA existe un periodo para ese semestre
        $periodo = Periodo::where(function ($query) use ($fechaInicio, $fechaFinal) {

            $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFinal])
                ->orWhereBetween('fecha_final', [$fechaInicio, $fechaFinal]);

        })->first();


        // Si no existe, crearlo
        if (!$periodo) {

            $periodo = Periodo::create([

                'nombre' => $nombrePeriodo,

                'fecha_inicio' => $fechaInicio,

                'fecha_final' => $fechaFinal,

                'fecha_inicio_1er_reporte' => $fechaInicio->copy()->addDays(5),

                'fecha_final_1er_reporte' => $fechaInicio->copy()->addDays(10),

                'fecha_inicio_2do_reporte' => $fechaInicio->copy()->addDays(28),

                'fecha_final_2do_reporte' => $fechaInicio->copy()->addDays(35),

                'fecha_inicio_reporte_final' => $fechaInicio->copy()->addDays(40),

                'fecha_final_reporte_final' => $fechaInicio->copy()->addDays(45),
            ]);
        }

        // 2. Crear más asesores
        $asesores = Asesor::factory(3)->create();

        $carreras = Carrera::all();

        foreach ($asesores as $asesor) {

            // asignar entre 1 y 2 carreras random
            $asesor->carreras()->attach(
                $carreras->random(rand(1,2))->pluck('id')
            );
        }

        // 3. Crear más estudiantes
        $estudiantes = Estudiante::factory(10)->create();

        // 4. Crear más proyectos y asignar relaciones
        foreach ($estudiantes as $estudiante) {

            $asesoresCompatibles = Asesor::whereHas('carreras', function ($query) use ($estudiante) {
            $query->where('carrera_id', $estudiante->carrera_id);
            })->get();
            
            // evitar error si no hay asesores
            if ($asesoresCompatibles->isEmpty()) {
                continue;
            }

            $asesor = $asesoresCompatibles->random();

            $proyecto = Proyecto::create([
                'nombre' => 'Proyecto de ' . $estudiante->nombre,
                'objetivo_general' => 'Objetivo de prueba',
                'justificacion' => 'Justificación de prueba',
                'informacion' => 'Información de prueba',
                'estudiante_id' => $estudiante->id,
                'asesor_id' => $asesor->id,
                'externo_id' => null,
                'empresa_id' => null,
                'periodo_id' => $periodo->id,
            ]);

            // Usuario para estudiante
            Usuario::create([
                'usa_id' => $estudiante->id,
                'usa_type' => Estudiante::class,
                'nombre_usuario' => $estudiante->correo_electronico,
                'contraseña' => Hash::make('1234'),
            ]);
        }

        // Usuarios para asesores
        foreach ($asesores as $asesor) {
            Usuario::create([
                'usa_id' => $asesor->id,
                'usa_type' => Asesor::class,
                'nombre_usuario' => $asesor->correo_electronico,
                'contraseña' => Hash::make('1234'),
            ]);
        }

        $this->info('Datos creados correctamente ');
    }
}
