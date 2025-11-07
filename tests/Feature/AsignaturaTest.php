<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Estudio;
use App\Models\Asignatura;

class AsignaturaTest extends TestCase
{
    use RefreshDatabase;

    public function test_requiere_parametro_idEstudio(): void
    {
        $response = $this->getJson('/api/asignaturas');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['idEstudio']);
    }

    public function test_valida_idEstudio_numerico(): void
    {
        $response = $this->getJson('/api/asignaturas?idEstudio=abc');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['idEstudio']);
    }

    public function test_valida_idEstudio_existe(): void
    {
        $response = $this->getJson('/api/asignaturas?idEstudio=999');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['idEstudio']);
    }

    public function test_devuelve_asignaturas_del_estudio_correcto(): void
    {
        $estudio1 = Estudio::factory()->create(['nombre' => 'DAW']);
        $estudio2 = Estudio::factory()->create(['nombre' => 'DAM']);

        Asignatura::factory()->create(['nombre' => 'Programación', 'idEstudio' => $estudio1->id]);
        Asignatura::factory()->create(['nombre' => 'Bases de Datos', 'idEstudio' => $estudio1->id]);
        Asignatura::factory()->create(['nombre' => 'Java', 'idEstudio' => $estudio2->id]);

        $response = $this->getJson('/api/asignaturas?idEstudio=' . $estudio1->id);

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonFragment(['nombre' => 'Programación'])
                 ->assertJsonFragment(['nombre' => 'Bases de Datos'])
                 ->assertJsonMissing(['nombre' => 'Java']);
    }

    public function test_ordenacion_por_nombre_ascendente(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);

        Asignatura::factory()->create(['nombre' => 'Programación', 'idEstudio' => $estudio->id]);
        Asignatura::factory()->create(['nombre' => 'Bases de Datos', 'idEstudio' => $estudio->id]);
        Asignatura::factory()->create(['nombre' => 'Lenguajes', 'idEstudio' => $estudio->id]);

        $response = $this->getJson('/api/asignaturas?idEstudio=' . $estudio->id . '&sortBy=nombre&sortOrder=asc');

        $response->assertStatus(200);

        $asignaturas = $response->json('data');
        $this->assertEquals('Bases de Datos', $asignaturas[0]['nombre']);
        $this->assertEquals('Lenguajes', $asignaturas[1]['nombre']);
        $this->assertEquals('Programación', $asignaturas[2]['nombre']);
    }

    public function test_ordenacion_por_id_descendente(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);

        $asig1 = Asignatura::factory()->create(['nombre' => 'Programación', 'idEstudio' => $estudio->id]);
        $asig2 = Asignatura::factory()->create(['nombre' => 'Bases de Datos', 'idEstudio' => $estudio->id]);
        $asig3 = Asignatura::factory()->create(['nombre' => 'Lenguajes', 'idEstudio' => $estudio->id]);

        $response = $this->getJson('/api/asignaturas?idEstudio=' . $estudio->id . '&sortBy=id&sortOrder=desc');

        $response->assertStatus(200);

        $asignaturas = $response->json('data');
        $this->assertEquals($asig3->id, $asignaturas[0]['id']);
        $this->assertEquals($asig2->id, $asignaturas[1]['id']);
        $this->assertEquals($asig1->id, $asignaturas[2]['id']);
    }

    public function test_respuesta_tiene_estructura_correcta(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        Asignatura::factory()->create(['nombre' => 'Programación', 'idEstudio' => $estudio->id]);

        $response = $this->getJson('/api/asignaturas?idEstudio=' . $estudio->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'nombre',
                             'idEstudio'
                         ]
                     ]
                 ]);
    }
}
