<?php

namespace Tests\Feature;

use App\Models\Modalidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModalidadTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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
        $response = $this->get(route('modalidades'));

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

        $response = $this->get(route('modalidades.get-data'));

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

        $response = $this->post(route('modalidades.search'), ['modd' => 'search_term']);

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

        $response = $this->post(route('modalidades.store'), $data);

        $response->assertRedirect(route('modalidades'));
        $this->assertDatabaseHas('modalidades', $data);
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

        $response = $this->put(route('modalidades.update', $modalidad->id), $data);

        $response->assertRedirect(route('modalidades'));
        $this->assertDatabaseHas('modalidades', array_merge(['id' => $modalidad->id], $data));
    }

    /**
     * Test the destroy method of ModalidadController.
     *
     * @return void
     */
    public function test_modalidad_destroy()
    {
        $modalidad = Modalidad::factory()->create();

        $response = $this->delete(route('modalidades.destroy', $modalidad->id));

        $response->assertRedirect(route('modalidades'));
        $this->assertSoftDeleted($modalidad);
    }
}
