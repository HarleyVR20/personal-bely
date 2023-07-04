<?php

namespace Tests\Feature;

use App\Models\Contrato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ContratoTest extends TestCase
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
     * Test the index method of ContratoController.
     *
     * @return void
     */
    public function test_contratos_screen_can_be_rendered(): void
    {
        $response = $this->get(route('contratos'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.contrato');
    }

    /**
     * Test the getData method of ContratoController.
     *
     * @return void
     */
    public function test_contratos_get_data()
    {
        $contratos = Contrato::factory()->count(5)->create();

        $response = $this->get(route('contratos.getData'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($contratos->count(), 'data');
    }

    /**
     * Test the search method of ContratoController.
     *
     * @return void
     */
    public function test_contratos_search()
    {
        $contratos = Contrato::factory()->count(5)->create();

        $searchTerm = $this->faker->word;

        $response = $this->get(route('contratos.search', ['q' => $searchTerm]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($contratos->count(), 'data');
    }

    /**
     * Test the store method of ContratoController.
     *
     * @return void
     */
    public function test_contratos_store()
    {
        $contratoData = Contrato::factory()->make()->toArray();

        $response = $this->post(route('contratos.store'), $contratoData);
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas('success');
    }

    /**
     * Test the update method of ContratoController.
     *
     * @return void
     */
    public function test_contratos_update()
    {
        $contrato = Contrato::factory()->create();
        $contratoData = Contrato::factory()->make()->toArray();

        $response = $this->put(route('contratos.update', ['id' => $contrato->id]), $contratoData);
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas('success');
    }

    /**
     * Test the destroy method of ContratoController.
     *
     * @return void
     */
    public function test_contratos_destroy()
    {
        $contrato = Contrato::factory()->create();

        $response = $this->delete(route('contratos.destroy', ['id' => $contrato->id]));
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas('success');
    }
}
