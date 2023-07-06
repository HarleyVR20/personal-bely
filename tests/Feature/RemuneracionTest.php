<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RemuneracionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use SoftDeletes;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario y autenticarlo
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }
    /**
     * Prueba el método index.
     *
     * @return void
     */
    public function test_remuneracion_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/remuneraciones', ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.remuneracion');
    }


    /**
     * Prueba el método getData.
     *
     * @return void
     */
    public function testGetData()
    {
        $response = $this->postJson('/personal/remuneraciones/data', [
            'id' => null,
            'empleado_id' => null,
            'Empleado' => null,
            'tipos_recorte' => [
                'nombres' => null,
                'ids' => null,
            ],
            'Contrato_id' => null,
            'Contrato' => null,
            'Concepto' => null,
            'Monto total' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'empleado_id',
                    'Empleado',
                    'tipos_recorte' => [
                        'nombres',
                        'ids',
                    ],
                    'Contrato_id',
                    'Contrato',
                    'Concepto',
                    'Monto total',
                    'creado en',
                    'actualizado en',
                ],
            ],
        ]);
    }

    /**
     * Prueba el método showCalendarDetails.
     *
     * @return void
     */
    public function testShowCalendarDetails()
    {
        // Simular una solicitud HTTP con datos de ejemplo
        $response = $this->post('/personal/remuneraciones/empleado', [
            'empleado' => 1,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.horario');
    }

    /**
     * Prueba el método store.
     *
     * @return void
     */
    public function testStore()
    {
        // Simular una solicitud HTTP con datos de ejemplo
        $response = $this->post('/personal/remuneraciones/', [
            'i_selectEmpleado' => 1,
            'i_selectTipoRecorte' => [1, 2, 3],
            'i_selectContrato' => 1,
            'i_concepto' => 'Remuneración de prueba',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Prueba el método update.
     *
     * @return void
     */
    public function testUpdate()
    {
        // Simular una solicitud HTTP con datos de ejemplo
        $response = $this->post('/personal/remuneraciones/1', [
            'e_selectEmpleado' => 1,
            'e_selectTipoRecorte' => [1, 2, 3],
            'e_selectContrato' => 1,
            'e_concepto' => 'Remuneración actualizada',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Prueba el método destroy.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Simular una solicitud HTTP para eliminar la remuneración con ID 1
        $response = $this->delete('/personal/remuneraciones/1', [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
