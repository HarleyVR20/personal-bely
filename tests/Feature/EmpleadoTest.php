<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmpleadoTest extends TestCase
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
     * Test the index method of EmpleadoController.
     *
     * @return void
     */
    public function test_empleado_screen_can_be_rendered(): void
    {
        $response = $this->get(route('empleados'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.empleado');
        $response->assertViewHasAll([
            'empleadoId',
            'columns',
            'data',
        ]);
    }
    /**
     * Test the getData method of EmpleadoController.
     *
     * @return void
     */
    public function test_empleado_get_ata()
    {
        $empleados = Empleado::factory()->create(5);

        $response = $this->get(route('empleados.get-data'));

        $response->assertStatus(200);
        $response->assertJsonCount($empleados->count(), 'data');
    }

    /**
     * Test the search method of EmpleadoController.
     *
     * @return void
     */
    public function test_empleado_search()
    {
        $empleados = Empleado::factory()->create(5);

        $response = $this->get(route('empleados.search', ['emp' => 'John']));

        $response->assertStatus(200);
        $response->assertJsonCount($empleados->count(), null);
    }

    /**
     * Test the store method of EmpleadoController.
     *
     * @return void
     */
    public function test_empleado_store()
    {
        $data = [
            'i_nombre' => 'John',
            'i_apellidos' => 'Doe',
            'i_dni' => '123456789',
            'i_fnacimiento' => '1990-01-01',
            'i_domicilio' => '123 Street',
            'i_celular' => '555-1234',
            'i_correo' => 'john.doe@example.com',
        ];

        $response = $this->post(route('empleados.store'), $data);

        $response->assertRedirect(route('empleados'));
        $this->assertDatabaseHas('empleados', $data);
    }

    /**
     * Test the update method of EmpleadoController.
     *
     * @return void
     */
    public function test_empleado_update()
    {
        $empleado = Empleado::factory()->create();

        $data = [
            'e_nombre' => 'Updated',
            'e_apellidos' => 'Employee',
            'e_dni' => '987654321',
            'e_fnacimiento' => '1990-01-01',
            'e_domicilio' => '456 Street',
            'e_celular' => '555-4321',
            'e_correo' => 'updated.employee@example.com',
        ];

        $response = $this->put(route('empleados.update', $empleado->id), $data);

        $response->assertRedirect(route('asistencias'));
        $this->assertDatabaseHas('empleados', array_merge(['id' => $empleado->id], $data));
    }

    /**
     * Test the destroy method of EmpleadoController.
     *
     * @return void
     */
    public function test_empleado_destroy()
    {
        $empleado = Empleado::factory()->create();

        $response = $this->delete(route('empleados.destroy', $empleado->id));

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Empleado eliminado con éxito']);
        $this->assertSoftDeleted($empleado);
    }
}
