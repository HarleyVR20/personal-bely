<?php

namespace Database\Factories;

use App\Models\TipoRecorte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recorte>
 */
class RecorteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo_recorte_id' => function () {
                return TipoRecorte::factory()->create()->id;
            },
            'monto_recorte' => $this->faker->randomFloat(2, 0, 999999.99),
            'observacion' => $this->faker->paragraph,
        ];
    }
}
