<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesor>
 */
class AsesorFactory extends Factory
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
            'profesion' => fake()->jobTitle(),
            'carrera' => 'Sistemas',
            'numero_cedula' => fake()->numberBetween(1000000,9999999),
        ];
    }
}
