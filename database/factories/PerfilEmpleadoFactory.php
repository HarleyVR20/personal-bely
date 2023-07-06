<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfilEmpleado>
 */
class PerfilEmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empleado_id' => function () {
                return Empleado::factory()->create()->id;
            },
            'profesion' => $this->faker->jobTitle,
            'cuenta_bancaria' => $this->faker->bankAccountNumber,
        ];
    }
}
