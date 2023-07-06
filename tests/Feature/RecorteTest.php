<?php

namespace Tests\Feature;

use App\Models\Recorte;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RecorteTest extends TestCase
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
     * Test the index method of RecorteController.
     *
     * @return void
     */

    public function test_recortes_screen_can_be_rendered(): void
    {
        $response = $this->get(route('recortes'), ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertViewIs('admin.recorte');
        $response->assertViewHasAll([
            'recorteId',
            'columns',
            'data',
        ]);
    }

    /**
     * Test the getData method of RecorteController.
     *
     * @return void
     */
    public function test_recortes_get_data()
    {
        $recortes = Recorte::factory()->create(5);

        $response = $this->postJson(route('recortes.data'), [
            'id' => null,
            'tipo_id' => null,
            'Tipo' => null,
            'Monto' => null,
            'Observaciones' => null,
            'Creado en' => null,
            'Actualizado en' => null,
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($recortes->count(), 'data');
    }

    /**
     * Test the store method of RecorteController.
     *
     * @return void
     */
    public function test_recortes_store()
    {
        $data = [
            'i_selectTipoRecorte' => 1,
            'i_monto_recortado' => 100,
            'i_observaciones' => 'Observaciones',
        ];

        $response = $this->post(route('recortes.store'), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recortes', $data);
    }

    /**
     * Test the update method of RecorteController.
     *
     * @return void
     */
    public function test_recortes_update()
    {
        $recorte = Recorte::factory()->create();

        $data = [
            'e_selectTipoRecorte' => 2,
            'e_monto_recortado' => 200,
            'e_observaciones' => 'Updated Observaciones',
        ];

        $response = $this->put(route('recortes.update', $recorte->id), $data, ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertDatabaseHas('recortes', array_merge(['id' => $recorte->id], [
            'tipo_recorte_id' => $data['e_selectTipoRecorte'],
            'monto_recorte' => $data['e_monto_recortado'],
            'observacion' => $data['e_observaciones'],
        ]));
    }

    /**
     * Test the destroy method of RecorteController.
     *
     * @return void
     */
    public function test_recortes_destroy()
    {
        $recorte = Recorte::factory()->create();

        $response = $this->delete(route('recortes.destroy', $recorte->id), [], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertRedirect();
        $this->assertSoftDeleted($recorte);
    }

    /**
     * Test the search method of RecorteController.
     *
     * @return void
     */
    public function test_recortes_search()
    {
        $recortes = Recorte::factory()->create(5);

        $searchTerm = $recortes[0]->observacion;

        $response = $this->postJson(route('recortes.search'), ['q' => $searchTerm], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($recortes->count(), 'data');
    }
}
