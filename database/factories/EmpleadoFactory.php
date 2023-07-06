<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'dni' => $this->faker->unique()->numerify('########'),
            'fecha_nacimiento' => $this->faker->date(),
            'domicilio_fiscal' => $this->faker->address,
            'telf' => $this->faker->phoneNumber,
            'correo' => $this->faker->email,
        ];
    }
}
