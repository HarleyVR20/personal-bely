<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\MotivoExoneracion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exoneracion>
 */
class ExoneracionFactory extends Factory
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
            'motivo_exoneracion_id' => function () {
                return MotivoExoneracion::factory()->create()->id;
            },
            'fecha_inicio' => $this->faker->dateTime(),
            'fecha_fin' => $this->faker->dateTime(),
            'observacion' => $this->faker->paragraph,
        ];
    }
}
