<?php

namespace Tests\Feature;

use App\Models\Exoneracion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExoneracionTest extends TestCase
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
    public function test_exoneracion_screen_can_be_rendered(): void
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
     * Test the index method of ExoneracionController.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('exoneraciones'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.exoneracion');
        $response->assertViewHasAll([
            'exoneracionId',
            'columns',
            'data',
        ]);
    }

    /**
     * Test the getData method of ExoneracionController.
     *
     * @return void
     */
    public function testGetData()
    {
        $exoneraciones = factory(Exoneracion::class, 5)->create();

        $response = $this->get(route('exoneraciones.get-data'));

        $response->assertStatus(200);
        $response->assertJsonCount($exoneraciones->count(), 'data');
    }

    /**
     * Test the store method of ExoneracionController.
     *
     * @return void
     */
    public function testStore()
    {
        $data = [
            'i_selectEmpleado' => 1,
            'i_selectMotivoExioneracion' => 1,
            'i_finicio' => '2023-01-01',
            'i_ffinal' => '2023-01-05',
            'i_observacion' => 'Test observación',
        ];

        $response = $this->post(route('exoneraciones.store'), $data);

        $response->assertRedirect(route('exoneraciones'));
        $this->assertDatabaseHas('exoneraciones', $data);
    }

    /**
     * Test the update method of ExoneracionController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $exoneracion = Exoneracion::factory()->create();

        $data = [
            'e_selectEmpleado' => 2,
            'e_selectMotivoExioneracion' => 2,
            'e_finicio' => '2023-02-01',
            'e_ffinal' => '2023-02-05',
            'e_observacion' => 'Updated observación',
        ];

        $response = $this->put(route('exoneraciones.update', $exoneracion->id), $data);

        $response->assertRedirect(route('exoneraciones'));
        $this->assertDatabaseHas('exoneraciones', array_merge(['id' => $exoneracion->id], $data));
    }

    /**
     * Test the destroy method of ExoneracionController.
     *
     * @return void
     */
    public function testDestroy()
    {
        $exoneracion = Exoneracion::factory()->create();

        $response = $this->delete(route('exoneraciones.destroy', $exoneracion->id));

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Eliminación realizada con éxito']);
        $this->assertDeleted($exoneracion);
    }
}
