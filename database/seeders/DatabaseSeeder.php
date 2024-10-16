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
            ActividadSeeder::class,
            AsesorSeeder::class,
            CarreraSeeder::class,
            ConfiguracionSeeder::class,
            EmpresaSeeder::class,
            //EspecificoSeeder::class,
            EstudianteSeeder::class,
            PeriodoSeeder::class,
            //PrimeroSeeder::class,
            ProyectoSeeder::class,
            //SeguimientoSeeder::class,
            //SegundoSeeder::class,
           // UltimoSeeder::class,
           UsuarioSeeder::class
        ]);
    }
    
}
