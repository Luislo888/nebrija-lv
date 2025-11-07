<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alumno;

class AlumnoTest extends TestCase
{
    use RefreshDatabase;

    public function test_paginacion_funciona_correctamente(): void
    {
        Alumno::factory()->count(15)->create();

        $response = $this->getJson('/api/alumnos?page=1&limit=10');

        $response->assertStatus(200)
                 ->assertJsonCount(10, 'data')
                 ->assertJsonPath('page', 1)
                 ->assertJsonPath('limit', 10)
                 ->assertJsonPath('total', 15)
                 ->assertJsonPath('totalPages', 2)
                 ->assertJsonPath('hasNext', true)
                 ->assertJsonPath('hasPrev', false);
    }

    public function test_paginacion_segunda_pagina(): void
    {
        Alumno::factory()->count(15)->create();

        $response = $this->getJson('/api/alumnos?page=2&limit=10');

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data')
                 ->assertJsonPath('page', 2)
                 ->assertJsonPath('hasNext', false)
                 ->assertJsonPath('hasPrev', true);
    }

    public function test_ordenacion_por_nombre_ascendente(): void
    {
        Alumno::factory()->create(['nombre' => 'Carlos García']);
        Alumno::factory()->create(['nombre' => 'Ana López']);
        Alumno::factory()->create(['nombre' => 'Beatriz Martínez']);

        $response = $this->getJson('/api/alumnos?sortBy=nombre&sortOrder=asc');

        $response->assertStatus(200);

        $alumnos = $response->json('data');
        $this->assertEquals('Ana López', $alumnos[0]['nombre']);
        $this->assertEquals('Beatriz Martínez', $alumnos[1]['nombre']);
        $this->assertEquals('Carlos García', $alumnos[2]['nombre']);
    }

    public function test_ordenacion_por_id_descendente(): void
    {
        $alumno1 = Alumno::factory()->create(['nombre' => 'Carlos']);
        $alumno2 = Alumno::factory()->create(['nombre' => 'Ana']);
        $alumno3 = Alumno::factory()->create(['nombre' => 'Beatriz']);

        $response = $this->getJson('/api/alumnos?sortBy=id&sortOrder=desc');

        $response->assertStatus(200);

        $alumnos = $response->json('data');
        $this->assertEquals($alumno3->id, $alumnos[0]['id']);
        $this->assertEquals($alumno2->id, $alumnos[1]['id']);
        $this->assertEquals($alumno1->id, $alumnos[2]['id']);
    }

    public function test_valida_limites_de_paginacion(): void
    {
        Alumno::factory()->count(5)->create();

        $response = $this->getJson('/api/alumnos?limit=150');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['limit']);
    }

    public function test_valida_page_numerico(): void
    {
        $response = $this->getJson('/api/alumnos?page=abc');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['page']);
    }

    public function test_valida_sortBy_permitido(): void
    {
        Alumno::factory()->count(5)->create();

        $response = $this->getJson('/api/alumnos?sortBy=email');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['sortBy']);
    }

    public function test_valida_sortOrder_permitido(): void
    {
        Alumno::factory()->count(5)->create();

        $response = $this->getJson('/api/alumnos?sortOrder=random');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['sortOrder']);
    }

    public function test_respuesta_tiene_estructura_correcta(): void
    {
        Alumno::factory()->create(['nombre' => 'Juan Pérez']);

        $response = $this->getJson('/api/alumnos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'nombre'
                         ]
                     ],
                     'page',
                     'limit',
                     'total',
                     'totalPages',
                     'hasNext',
                     'hasPrev'
                 ]);
    }

    public function test_valores_por_defecto_de_paginacion(): void
    {
        Alumno::factory()->count(15)->create();

        $response = $this->getJson('/api/alumnos');

        $response->assertStatus(200)
                 ->assertJsonPath('page', 1)
                 ->assertJsonPath('limit', 10);
    }
}
