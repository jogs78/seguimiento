<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            AsesorSeeder::class,
            ExternoSeeder::class,
            CoordinadorSeeder::class,
            CarreraSeeder::class,
            AsesorCarreraSeeder::class,
            ConfiguracionSeeder::class,
            EmpresaSeeder::class,
            //EspecificoSeeder::class,
            PeriodoSeeder::class,
            ProyectoSeeder::class,
            ActividadSeeder::class,
            EstudianteSeeder::class,
            //PrimeroSeeder::class,
            //SeguimientoSeeder::class,
            //SegundoSeeder::class,
            //UltimoSeeder::class,
            UsuarioSeeder::class,
            
            ParcialSeeder::class,
            //finales
        ]);
    }
    
}
