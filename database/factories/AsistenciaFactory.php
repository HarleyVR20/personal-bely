<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asistencia>
 */
class AsistenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dia' => $this->faker->date(),
            'hora_entrada' => $this->faker->time(),
            'hora_salida' => $this->faker->time(),
            'empleado_id' => function () {
                return Empleado::factory()->create()->id;
            },
            'area_id' => function () {
                return Area::factory()->create()->id;
            },
            'estado' => $this->faker->numberBetween(0, 1),
        ];
    }
}
