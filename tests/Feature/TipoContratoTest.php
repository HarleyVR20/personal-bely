<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TipoContratoTest extends TestCase
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
    public function test_tipo_de_contrato_screen_can_be_rendered(): void
    {
        $response = $this->get('/personal/tipo-de-contratos');
        $response->assertStatus(200);
    }
}
