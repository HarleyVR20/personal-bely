<?php

namespace Database\Factories;

use App\Models\Recorte;
use App\Models\Remuneracion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RemuneracionRecorte>
 */
class RemuneracionRecorteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'remuneracion_id' => function () {
                return Remuneracion::factory()->create()->id;
            },
            'recorte_id' => function () {
                return Recorte::factory()->create()->id;
            },
        ];
    }
}
