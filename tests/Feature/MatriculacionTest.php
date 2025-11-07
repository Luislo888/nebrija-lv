<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Estudio;
use App\Models\Asignatura;
use App\Models\Alumno;

class MatriculacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_join_devuelve_datos_correctos(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        $asignatura = Asignatura::factory()->create([
            'nombre' => 'Programación',
            'idEstudio' => $estudio->id
        ]);
        $alumno = Alumno::factory()->create(['nombre' => 'Juan Pérez']);

        $alumno->asignaturas()->attach($asignatura->id);

        $response = $this->getJson('/api/alumnos-asignaturas');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment([
                     'alumnoNombre' => 'Juan Pérez',
                     'asignaturaNombre' => 'Programación',
                     'estudioNombre' => 'DAW'
                 ]);
    }

    public function test_paginacion_funciona_correctamente(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        $asignatura = Asignatura::factory()->create([
            'nombre' => 'Programación',
            'idEstudio' => $estudio->id
        ]);
        $alumnos = Alumno::factory()->count(15)->create();

        foreach ($alumnos as $alumno) {
            $alumno->asignaturas()->attach($asignatura->id);
        }

        $response = $this->getJson('/api/alumnos-asignaturas?page=1&limit=10');

        $response->assertStatus(200)
                 ->assertJsonCount(10, 'data')
                 ->assertJsonPath('page', 1)
                 ->assertJsonPath('limit', 10)
                 ->assertJsonPath('total', 15)
                 ->assertJsonPath('totalPages', 2)
                 ->assertJsonPath('hasNext', true)
                 ->assertJsonPath('hasPrev', false);
    }

    public function test_ordenacion_por_alumno_nombre(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        $asignatura = Asignatura::factory()->create([
            'nombre' => 'Programación',
            'idEstudio' => $estudio->id
        ]);

        $alumno1 = Alumno::factory()->create(['nombre' => 'Carlos García']);
        $alumno2 = Alumno::factory()->create(['nombre' => 'Ana López']);
        $alumno3 = Alumno::factory()->create(['nombre' => 'Beatriz Martínez']);

        $alumno1->asignaturas()->attach($asignatura->id);
        $alumno2->asignaturas()->attach($asignatura->id);
        $alumno3->asignaturas()->attach($asignatura->id);

        $response = $this->getJson('/api/alumnos-asignaturas?sortBy=alumnoNombre&sortOrder=asc');

        $response->assertStatus(200);

        $matriculaciones = $response->json('data');
        $this->assertEquals('Ana López', $matriculaciones[0]['alumnoNombre']);
        $this->assertEquals('Beatriz Martínez', $matriculaciones[1]['alumnoNombre']);
        $this->assertEquals('Carlos García', $matriculaciones[2]['alumnoNombre']);
    }

    public function test_ordenacion_por_asignatura_nombre(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        $alumno = Alumno::factory()->create(['nombre' => 'Juan Pérez']);

        $asig1 = Asignatura::factory()->create(['nombre' => 'Programación', 'idEstudio' => $estudio->id]);
        $asig2 = Asignatura::factory()->create(['nombre' => 'Bases de Datos', 'idEstudio' => $estudio->id]);
        $asig3 = Asignatura::factory()->create(['nombre' => 'Lenguajes', 'idEstudio' => $estudio->id]);

        $alumno->asignaturas()->attach([$asig1->id, $asig2->id, $asig3->id]);

        $response = $this->getJson('/api/alumnos-asignaturas?sortBy=asignaturaNombre&sortOrder=asc');

        $response->assertStatus(200);

        $matriculaciones = $response->json('data');
        $this->assertEquals('Bases de Datos', $matriculaciones[0]['asignaturaNombre']);
        $this->assertEquals('Lenguajes', $matriculaciones[1]['asignaturaNombre']);
        $this->assertEquals('Programación', $matriculaciones[2]['asignaturaNombre']);
    }

    public function test_ordenacion_por_estudio_nombre(): void
    {
        $estudio1 = Estudio::factory()->create(['nombre' => 'DAW']);
        $estudio2 = Estudio::factory()->create(['nombre' => 'ASIR']);
        $estudio3 = Estudio::factory()->create(['nombre' => 'DAM']);

        $alumno = Alumno::factory()->create(['nombre' => 'Juan Pérez']);

        $asig1 = Asignatura::factory()->create(['nombre' => 'Asignatura 1', 'idEstudio' => $estudio1->id]);
        $asig2 = Asignatura::factory()->create(['nombre' => 'Asignatura 2', 'idEstudio' => $estudio2->id]);
        $asig3 = Asignatura::factory()->create(['nombre' => 'Asignatura 3', 'idEstudio' => $estudio3->id]);

        $alumno->asignaturas()->attach([$asig1->id, $asig2->id, $asig3->id]);

        $response = $this->getJson('/api/alumnos-asignaturas?sortBy=estudioNombre&sortOrder=asc');

        $response->assertStatus(200);

        $matriculaciones = $response->json('data');
        $this->assertEquals('ASIR', $matriculaciones[0]['estudioNombre']);
        $this->assertEquals('DAM', $matriculaciones[1]['estudioNombre']);
        $this->assertEquals('DAW', $matriculaciones[2]['estudioNombre']);
    }

    public function test_orden_secundario_se_aplica_correctamente(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        $asignatura = Asignatura::factory()->create([
            'nombre' => 'Programación',
            'idEstudio' => $estudio->id
        ]);

        $alumno1 = Alumno::factory()->create(['nombre' => 'Carlos']);
        $alumno2 = Alumno::factory()->create(['nombre' => 'Ana']);

        $alumno1->asignaturas()->attach($asignatura->id);
        $alumno2->asignaturas()->attach($asignatura->id);

        $response = $this->getJson('/api/alumnos-asignaturas?sortBy=asignaturaNombre&sortOrder=asc');

        $response->assertStatus(200);

        $matriculaciones = $response->json('data');
        $this->assertEquals('Ana', $matriculaciones[0]['alumnoNombre']);
        $this->assertEquals('Carlos', $matriculaciones[1]['alumnoNombre']);
    }

    public function test_valida_sortBy_permitido(): void
    {
        $response = $this->getJson('/api/alumnos-asignaturas?sortBy=email');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['sortBy']);
    }

    public function test_respuesta_tiene_estructura_correcta(): void
    {
        $estudio = Estudio::factory()->create(['nombre' => 'DAW']);
        $asignatura = Asignatura::factory()->create([
            'nombre' => 'Programación',
            'idEstudio' => $estudio->id
        ]);
        $alumno = Alumno::factory()->create(['nombre' => 'Juan Pérez']);

        $alumno->asignaturas()->attach($asignatura->id);

        $response = $this->getJson('/api/alumnos-asignaturas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'alumnoId',
                             'alumnoNombre',
                             'asignaturaId',
                             'asignaturaNombre',
                             'estudioId',
                             'estudioNombre'
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

    public function test_sin_matriculaciones_devuelve_array_vacio(): void
    {
        $response = $this->getJson('/api/alumnos-asignaturas');

        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data')
                 ->assertJsonPath('total', 0);
    }
}
