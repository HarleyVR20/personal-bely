<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AsistenciaTest extends TestCase
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
    public function test_asistencia_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/personal/asistencia');

        $response->assertStatus(200);
        $response->assertViewIs('admin.asistencia');
        $response->assertViewHas(['asistenciaId', 'columns', 'data']);
    }

    public function test_asistencia_get_data(): void
    {
        $asistencia = Asistencia::factory()->create();

        $response = $this->getJson('/personal/personal/asistencia/data');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'id' => $asistencia->id,
            'empleado_id' => $asistencia->empleado_id,
            'día' => $asistencia->dia,
            'hora entrada' => $asistencia->hora_entrada,
            'hora salida' => $asistencia->hora_salida,
            'estado' => $asistencia->estado,
            // ...comprobar otros campos
        ]);
    }
    public function test_asistencia_store(): void
    {
        // Simular datos de formulario válidos
        $data = [
            'i_selectEmpleado' => 1,
            'i_area' => 1,
            'i_asistencia' => 'presente',
            'i_fecha' => '2023-07-04',
            // ...otros campos necesarios
        ];

        $response = $this->post('/personal/personal/asistencia', $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('asistencias', [
            'empleado_id' => 1,
            'area_id' => 1,
            'estado' => 'presente',
            'dia' => '2023-07-04',
            // ...comprobar otros campos
        ]);
    }
    public function test_asistencia_update(): void
    {
        $asistencia = Asistencia::factory()->create();

        // Simular datos de formulario válidos
        $data = [
            'e_selectEmpleado' => 2,
            'e_area' => 2,
            'e_asistencia' => 'ausente',
            'e_fecha' => '2023-07-05',
            // ...otros campos necesarios
        ];

        $response = $this->put('/personal/asistencia/' . $asistencia->id, $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('asistencias', [
            'id' => $asistencia->id,
            'empleado_id' => 2,
            'area_id' => 2,
            'estado' => 'ausente',
            'dia' => '2023-07-05',
            // ...comprobar otros campos
        ]);
    }
    /**
     * Prueba la función destroy().
     */
    public function test_asistencia_destroy()
    {
        $asistencia = Asistencia::factory()->create();

        $response = $this->delete('/personal/asistencia/' . $asistencia->id);

        $response->assertRedirect();
        $this->assertSoftDeleted($asistencia);
    }

    /**
     * Prueba la función import().
     */
    // public function test_asistencia_import()
    // {
    // Simular archivo Excel válido
    // $file = storage_path('test_files/asistencias.xlsx');
    // $data = ['excel_file' => new \Illuminate\Http\UploadedFile($file, 'asistencias.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true)];

    // $response = $this->post('/asistencia/import', $data);

    // $response->assertRedirect();
    // $response->assertSessionHas('success');
    // }
}
