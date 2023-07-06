<?php

namespace Tests\Feature;

use App\Models\Modalidad;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ModalidadTest extends TestCase
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
     * Test the index method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_screen_can_be_rendered(): void
    {
        $response = $this->get(route('modalidades'), ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.modalidad');
        $response->assertViewHasAll([
            'modalidadId',
            'columns',
            'data',
        ]);
    }

    /**
     * Test the getData method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_get_data()
    {
        $modalidades = Modalidad::factory()->create(5);

        $response = $this->postJson(route('modalidades.data'), [
            'id' => null,
            'nombre de modalidad' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($modalidades->count(), 'data');
    }

    /**
     * Test the search method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_search()
    {
        $modalidades = Modalidad::factory()->create(5);

        $response = $this->postJson(route('modalidades.search'), ['modd' => $modalidades[0]->name_mod], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($modalidades->count(), 'data');
    }

    /**
     * Test the store method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_store()
    {
        $data = [
            'i_modalidad' => 'Modalidad 1',
        ];

        $response = $this->post(route('modalidades.store'), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertDatabaseHas('modalidades', array_merge(['name_mod' => $data['e_modalidad']]));
    }

    /**
     * Test the update method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_update()
    {
        $modalidad = Modalidad::factory()->create();

        $data = [
            'e_modalidad' => 'Updated Modalidad',
        ];

        $response = $this->put(route('modalidades.update', $modalidad->id), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertDatabaseHas('modalidades', array_merge(['name_mod' => $modalidad->e_modalidad]));
    }

    /**
     * Test the destroy method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_destroy()
    {
        $modalidad = Modalidad::factory()->create();

        $response = $this->delete(route('modalidades.destroy', $modalidad->id), [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertSoftDeleted($modalidad);
    }
}
