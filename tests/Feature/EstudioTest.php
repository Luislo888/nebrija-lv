<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Estudio;

class EstudioTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_obtener_listado_de_estudios(): void
    {
        Estudio::factory()->create(['nombre' => 'DAW']);
        Estudio::factory()->create(['nombre' => 'DAM']);
        Estudio::factory()->create(['nombre' => 'ASIR']);

        $response = $this->getJson('/api/estudios');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'nombre']
                     ]
                 ])
                 ->assertJsonCount(3, 'data');
    }

    public function test_estudios_estan_ordenados_por_nombre(): void
    {
        Estudio::factory()->create(['nombre' => 'ASIR']);
        Estudio::factory()->create(['nombre' => 'DAW']);
        Estudio::factory()->create(['nombre' => 'DAM']);

        $response = $this->getJson('/api/estudios');

        $response->assertStatus(200);

        $estudios = $response->json('data');
        $this->assertEquals('ASIR', $estudios[0]['nombre']);
        $this->assertEquals('DAM', $estudios[1]['nombre']);
        $this->assertEquals('DAW', $estudios[2]['nombre']);
    }

    public function test_respuesta_tiene_estructura_correcta(): void
    {
        Estudio::factory()->create(['nombre' => 'DAW']);

        $response = $this->getJson('/api/estudios');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'nombre'
                         ]
                     ]
                 ]);
    }
}
