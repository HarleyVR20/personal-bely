<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AsistenciaTest extends TestCase
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
     * Prueba la función index().
     */
    public function test_asistencia_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/asistencias', ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.asistencia');
        $response->assertViewHas(['asistenciaId', 'columns', 'data']);
    }

    public function test_asistencia_get_data(): void
    {
        $asistencia = Asistencia::factory()->create();

        $response = $this->postJson('/personal/asistencias/data', [
            'id' => null,
            'empleado_id' => null,
            'empleado' => null,
            'area_id' => null,
            'área' => null,
            'día' => null,
            'hora entrada' => null,
            'hora salida' => null,
            'estado' => null,
            'creado en' => null,
            'actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'id' => $asistencia->id,
            'empleado_id' => $asistencia->empleado_id,
            'día' => $asistencia->dia,
            'hora entrada' => $asistencia->hora_entrada,
            'hora salida' => $asistencia->hora_salida,
            'estado' => $asistencia->estado,
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

        $response = $this->post('/personal/asistencias', $data, ['X-CSRF-TOKEN' => csrf_token()]);

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

        $response = $this->put('/personal/asistencias/' . $asistencia->id, $data, ['X-CSRF-TOKEN' => csrf_token()]);

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

        $response = $this->delete('/personal/asistencias/' . $asistencia->id, [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertSoftDeleted($asistencia);
        $this->assertSoftDeleted('asistencias', ['id' => $asistencia->id]);
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
