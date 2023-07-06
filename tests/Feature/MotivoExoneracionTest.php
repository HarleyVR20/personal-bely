<?php

namespace Tests\Feature;

use App\Models\MotivoExoneracion;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class MotivoExoneracionTest extends TestCase
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
     * Test the index method of MotivoExoneracionController.
     *
     * @return void
     */
    public function test_motivo_exoneracion_screen_can_be_rendered(): void
    {
        $response = $this->get(route('motivo-exoneraciones'), ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.motivo_exoneracion');
        $response->assertViewHasAll([
            'motivoExoneracionId',
            'columns',
            'data',
        ]);
    }

    /**
     * Test the getData method of MotivoExoneracionController.
     *
     * @return void
     */
    public function test_motivo_exoneracion_get_data()
    {
        $motivoExoneraciones = MotivoExoneracion::factory()->create(5);

        $response = $this->postJson(route('motivo-exoneraciones.data'), [
            'id' => null,
            'descripción' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($motivoExoneraciones->count(), 'data');
    }

    /**
     * Test the search method of MotivoExoneracionController.
     *
     * @return void
     */
    public function test_motivo_exoneracion_search()
    {
        $motivoExoneraciones = MotivoExoneracion::factory()->create(5);

        $response = $this->postJson(route('motivo-exoneraciones.search'), ['q' => $motivoExoneraciones[0]->description], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($motivoExoneraciones->count(), 'data');
    }

    /**
     * Test the store method of MotivoExoneracionController.
     *
     * @return void
     */
    public function test_motivo_exoneracion_store()
    {
        $data = [
            'i_descripcion' => 'Motivo Exoneracion 1',
        ];

        $response = $this->post(route('motivo-exoneraciones.store'), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertDatabaseHas('motivo_exoneraciones', $data);
    }

    /**
     * Test the update method of MotivoExoneracionController.
     *
     * @return void
     */
    public function test_motivo_exoneracion_update()
    {
        $motivoExoneracion = MotivoExoneracion::factory()->create();

        $data = [
            'e_descripcion' => 'Updated Motivo Exoneracion',
        ];

        $response = $this->put(route('motivo-exoneraciones.update', $motivoExoneracion->id), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertDatabaseHas('motivo_exoneraciones', array_merge(['id' => $motivoExoneracion->id], $data));
    }

    /**
     * Test the destroy method of MotivoExoneracionController.
     *
     * @return void
     */
    public function test_motivo_exoneracion_destroy()
    {
        $motivoExoneracion = MotivoExoneracion::factory()->create();

        $response = $this->delete(route('motivo-exoneraciones.destroy', $motivoExoneracion->id, ['X-CSRF-TOKEN' => csrf_token()]));

        $response->assertRedirect();
        $this->assertSoftDeleted($motivoExoneracion);
    }
}
