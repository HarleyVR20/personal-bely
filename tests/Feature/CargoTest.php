<?php

namespace Tests\Feature;

use App\Models\Cargo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CargoTest extends TestCase
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
     * Prueba la función index().
     */
    public function test_cargo_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/personal/cargos');
        $response->assertStatus(200);

        $response->assertViewIs('admin.cargo');
        $response->assertViewHas(['cargoId', 'columns', 'data']);
    }

    /**
     * Prueba la función getData().
     */
    public function test_cargo_get_data()
    {
        $cargo = Cargo::factory()->create();

        $response = $this->getJson('/personal/cargo/data');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'id' => $cargo->id,
            'area_id' => $cargo->area_id,
            'área' => $cargo->area->gerencia . ' - ' . $cargo->area->sub_gerencia,
            'nombre' => $cargo->nombre,
            'creado en' => optional($cargo->created_at)->toDateTimeString(),
            'actualizado en' => optional($cargo->updated_at)->toDateTimeString(),
        ]);
    }

    /**
     * Prueba la función store().
     */
    public function test_cargo_store()
    {
        // Simular datos de formulario válidos
        $data = [
            'i_area' => 1,
            'i_cargo' => 'Gerente',
        ];

        $response = $this->post('/personal/cargo', $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('cargos', [
            'area_id' => 1,
            'nombre' => 'Gerente',
        ]);
    }

    /**
     * Prueba la función update().
     */
    public function test_cargo_update()
    {
        $cargo = Cargo::factory()->create();

        // Simular datos de formulario válidos
        $data = [
            'e_area' => 2,
            'e_cargo' => 'Supervisor',
        ];

        $response = $this->put('/personal/cargo/' . $cargo->id, $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('cargos', [
            'id' => $cargo->id,
            'area_id' => 2,
            'nombre' => 'Supervisor',
        ]);
    }

    /**
     * Prueba la función destroy().
     */
    public function test_cargo_destroy()
    {
        $cargo = Cargo::factory()->create();

        $response = $this->delete('/personal/cargo/' . $cargo->id);

        $response->assertRedirect();
        $this->assertSoftDeleted($cargo);
    }
}
