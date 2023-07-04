<?php

use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AreaTest extends TestCase
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

    public function test_area_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/areas');
        $response->assertStatus(200);
    }

    public function test_area_index_returns_view(): void
    {
        $response = $this->get('/personal/areas');
        $response->assertStatus(200);
        $response->assertViewIs('admin.area');
        $response->assertViewHasAll([
            'areaId',
            'columns',
            'data'
        ]);
    }

    public function test_area_data_returns_valid_json(): void
    {
        $response = $this->post('/personal/areas/data', [
            'id',
            'gerencia',
            'sub area',
            'creado en',
            'actualizado en',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'gerencia',
                    'sub area',
                    'creado en',
                    'actualizado en',
                ],
            ],
        ]);
    }

    public function test_area_search_returns_valid_json(): void
    {
        $term = $this->faker->word;

        $response = $this->post('/personal/areas/search', ['q' => $term]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }

    public function test_area_store_creates_new_area(): void
    {
        $gerencia = substr($this->faker->word, 1, 100);
        $subArea = substr($this->faker->word, 1, 100);

        $response = $this->post('/personal/areas', [
            'i_gerencia' => $gerencia,
            'i_sub_area' => $subArea,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('areas', [
            'gerencia' => $gerencia,
            'sub_area' => $subArea,
        ]);
    }

    public function test_area_update_updates_existing_area(): void
    {
        $area = Area::factory()->create();

        $newGerencia = substr($this->faker->word, 1, 100);
        $newSubArea = substr($this->faker->word, 1, 100);

        $response = $this->put(route('areas.update', ['id' => $area->id]), [
            'e_gerencia' => $newGerencia,
            'e_sub_area' => $newSubArea,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('areas', [
            'gerencia' => $newGerencia,
            'sub_area' => $newSubArea,
        ]);
    }

    public function test_area_destroy_deletes_area(): void
    {
        $area = Area::factory()->create();

        $response = $this->delete(route('areas.destroy', ['id' => $area->id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($area);
    }


    public function test_area_import_returns_success_message(): void
    {
        // Asegúrate de tener un archivo Excel válido para importar
        // y reemplaza 'path/to/excel/file.xlsx' con la ruta real del archivo

        // $response = $this->post('/personal/areas/import', [
        //     'excel_file' => new \Illuminate\Http\UploadedFile('path/to/excel/file.xlsx', 'file.xlsx'),
        // ]);

        // $response->assertRedirect()->back();
        // $response->assertSessionHas('success');
    }
}
