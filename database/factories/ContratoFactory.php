<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\Modalidad;
use App\Models\TipoContrato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contrato>
 */
class ContratoFactory extends Factory
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
            'tipo_contrato_id' => function () {
                return TipoContrato::factory()->create()->id;
            },
            'modalidad_id' => function () {
                return Modalidad::factory()->create()->id;
            },
            'marco_legal' => $this->faker->paragraph,
            'observacion' => $this->faker->paragraph,
            'fecha_vinculacion' => $this->faker->date(),
            'fecha_retiro' => $this->faker->date(),
            'dias_laborales' => json_encode([$this->faker->randomElement(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'])]),
            'horario_entrada' => $this->faker->time(),
            'horario_salida' => $this->faker->time(),
            'salario_base' => $this->faker->randomFloat(2, 0, 999999.99),
        ];
    }
}
