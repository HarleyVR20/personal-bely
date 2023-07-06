<?php

namespace Tests\Feature;

use App\Models\TipoContrato;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TipoContratoTest extends TestCase
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
     * Test para la función index del controlador TipoContratoController.
     */

    public function test_tipo_de_contrato_screen_can_be_rendered(): void
    {

        $response = $this->get('/personal/tipo-de-contratos', ['X-CSRF-TOKEN' => csrf_token(),]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.tipo_contrato');
        $response->assertViewHas('tipoContratoId');
        $response->assertViewHas('columns');
        $response->assertViewHas('data');
    }

    /**
     * Test para la función getData del controlador TipoContratoController.
     */
    public function test_tipo_de_contrato_get_data()
    {
        $tipoContrato = TipoContrato::factory()->create(3);

        $response = $this->postJson('/personal/tipo-de-contratos/data', [
            'id' => null,
            'tipo de contrato' => null,
            'plazo' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'tipo de contrato',
                    'plazo',
                    'creado en',
                    'actualizado en',
                ]
            ]
        ]);
    }

    /**
     * Test para la función search del controlador TipoContratoController.
     */
    public function test_tipo_de_contrato_search()
    {
        $tipoContrato = TipoContrato::factory()->create([
            'tipo' => 'Tipo de Contrato 1'

        ]);

        $response = $this->post('/personal/tipo-de-contratos/search', ['q' => 'Tipo de Contrato'], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            ['id' => $tipoContrato->id, 'text' => $tipoContrato->tipo]
        ]);
    }

    /**
     * Test para la función store del controlador TipoContratoController.
     */
    public function test_tipo_de_contrato_store()
    {
        $response = $this->post('/personal/tipo-de-contratos', [
            'i_tipo_contrato' => 'Nuevo Tipo de Contrato',
            'i_plazo' => '12'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tipo_contratos', [
            'tipo' => 'Nuevo Tipo de Contrato',
            'plazo' => '12'
        ]);
    }

    /**
     * Test para la función update del controlador TipoContratoController.
     */
    public function test_tipo_de_contrato_update()
    {
        $tipoContrato = TipoContrato::factory()->create();

        $response = $this->put('/personal/tipo-de-contratos/' . $tipoContrato->id, [
            'e_tipo_contrato' => 'Tipo de Contrato Actualizado',
            'e_plazo' => '24'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tipo_contratos', [
            'id' => $tipoContrato->id,
            'tipo' => 'Tipo de Contrato Actualizado',
            'plazo' => '24'
        ]);
    }

    /**
     * Test para la función destroy del controlador TipoContratoController.
     */
    public function test_tipo_de_contrato_destroy()
    {
        $tipoContrato = TipoContrato::factory()->create();

        $response = $this->delete('/personal/tipo-contratos/' . $tipoContrato->id, [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('tipo_contratos', ['id' => $tipoContrato->id]);
    }
}
