<?php

namespace Tests\Feature;

use App\Models\TipoRecorte;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TipoRecorteTest extends TestCase
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
     * Test para la función index del controlador TipoRecorteController.
     */
    public function test_tipo_de_recorte_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/tipo-de-recortes', ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.tipo_recorte');
        $response->assertViewHas('tipoRecorteId');
        $response->assertViewHas('columns');
        $response->assertViewHas('data');
    }

    /**
     * Test para la función getData del controlador TipoRecorteController.
     */
    public function test_tipo_de_recorte_get_data()
    {
        $tipoRecorte = TipoRecorte::factory()->create(3);

        $response = $this->postJson('/personal/tipo-de-recortes/data', [
            'id' => null,
            'descripcion' => null,
            'tipo' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'tipo',
                    'descripcion',
                    'creado en',
                    'actualizado en',
                ]
            ]
        ]);
    }

    /**
     * Test para la función search del controlador TipoRecorteController.
     */
    public function test_tipo_de_recorte_search()
    {
        $tipoRecorte = TipoRecorte::factory()->create([
            'description' => 'Tipo de Recorte 1'
        ]);

        $response = $this->post('/personal/tipo-de-recortes/search', [
            'q' => 'Tipo de Recorte'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJson([
            ['id' => $tipoRecorte->id, 'text' => $tipoRecorte->description]
        ]);
    }

    /**
     * Test para la función store del controlador TipoRecorteController.
     */
    public function test_tipo_de_recorte_store()
    {
        $response = $this->post('/personal/tipo-de-recortes', [
            'i_tipo' => 'Recorte',
            'i_descripcion' => 'Nuevo Tipo de Recorte',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tipo_recortes', [
            'tipo' => 'Recorte',
            'description' => 'Nuevo Tipo de Recorte',
        ]);
    }

    /**
     * Test para la función update del controlador TipoRecorteController.
     */
    public function test_tipo_de_recorte_update()
    {
        $tipoRecorte = TipoRecorte::factory()->create();

        $response = $this->put('/personal/tipo-de-recortes/' . $tipoRecorte->id, [
            'e_tipo' => 'Bonificación',
            'e_descripcion' => 'Tipo de Recorte Actualizado',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tipo_recortes', [
            'id' => $tipoRecorte->id,
            'tipo' => 'Bonificación',
            'description' => 'Tipo de Recorte Actualizado',
        ]);
    }

    /**
     * Test para la función destroy del controlador TipoRecorteController.
     */
    public function test_tipo_de_recorte_destroy()
    {
        $tipoRecorte = TipoRecorte::factory()->create();

        $response = $this->delete('/personal/tipo-de-recortes/' . $tipoRecorte->id, [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('tipo_recortes', ['id' => $tipoRecorte->id, 'description' => $tipoRecorte->description, 'tipo' => $tipoRecorte->tipo]);
    }
}
