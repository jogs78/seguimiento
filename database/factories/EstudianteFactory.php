<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'correo_electronico' => fake()->unique()->safeEmail(),
            'numero_de_control' => fake()->unique()->numerify('20######'),
            'telefono' => fake()->numerify('55########'),
            'direccion' => fake()->address(),
            'institucion_seguridad_social' => fake()->randomElement(['IMSS','ISSSTE','OTROS']),
            'numero_de_seguridad_social' => fake()->numberBetween(100000000,999999999),

            // Relaciones (IMPORTANTE), asignar si existe del 1-5, sino null
            'carrera_id' => fake()->optional()->numberBetween(1, 5),
            'proyecto_id' => null, // Se asignará después de crear el proyecto, para evitar problemas de integridad referencial 
        ];
    }
}
