<?php

namespace Database\Factories;

use App\Models\Contrato;
use App\Models\Empleado;
use App\Models\Recorte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Remuneracion>
 */
class RemuneracionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recorte_id' => function () {
                return Recorte::factory()->create()->id;
            },
            'empleado_id' => function () {
                return Empleado::factory()->create()->id;
            },
            'contrato_id' => function () {
                return Contrato::factory()->create()->id;
            },
            'concepto' => $this->faker->sentence,
            'monto_total' => 0,
        ];
    }
}
