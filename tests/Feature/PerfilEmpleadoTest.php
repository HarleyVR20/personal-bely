<?php

namespace Tests\Feature;

use App\Models\PerfilEmpleado;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PerfilEmpleadoTest extends TestCase
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
     * Test the index method of PerfilEmpleadoController.
     *
     * @return void
     */
    public function test_perfil_de_empleado_screen_can_be_rendered(): void
    {
        $response = $this->get(route('perfil-empleados'), ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.perfil_empleado');
        $response->assertViewHasAll([
            'perfilEmpleadoId',
            'columns',
            'data',
        ]);
    }

    /**
     * Test the getData method of PerfilEmpleadoController.
     *
     * @return void
     */
    public function test_perfil_de_empleado_get_data()
    {
        $perfilEmpleados = PerfilEmpleado::factory()->create(5);

        $response = $this->postJson(route('perfil-empleados.data'), [
            'id' => null,
            'empleado_id'  => null,
            'empleado' => null,
            'profesión' => null,
            'cuenta bancaria' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($perfilEmpleados->count(), 'data');
    }

    /**
     * Test the store method of PerfilEmpleadoController.
     *
     * @return void
     */
    public function test_perfil_de_empleado_store()
    {
        $data = [
            'i_selectEmpleado' => 1,
            'i_profesion' => 'Profesión 1',
            'i_cuenta_bancaria' => '1234567890',
        ];

        $response = $this->post(route('perfil-empleados.store'), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
    }

    /**
     * Test the update method of PerfilEmpleadoController.
     *
     * @return void
     */
    public function test_perfil_de_empleado_update()
    {
        $perfilEmpleado = PerfilEmpleado::factory()->create();

        $data = [
            'e_selectEmpleado' => 2,
            'e_profesion' => 'Updated Profesión',
            'e_cuenta_bancaria' => '9876543210',
        ];

        $response = $this->put(route('perfil-empleados.update', $perfilEmpleado->id), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
    }

    /**
     * Test the destroy method of PerfilEmpleadoController.
     *
     * @return void
     */
    public function test_perfil_de_empleado_destroy()
    {
        $perfilEmpleado = PerfilEmpleado::factory()->create();

        $response = $this->delete(route('perfil-empleados.destroy', $perfilEmpleado->id), [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertSoftDeleted($perfilEmpleado);
    }
}
