<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignaturaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('asignatura')->insert([
            ['nombre' => 'Desarrollo Web en Entorno Cliente', 'idEstudio' => 1],
            ['nombre' => 'Desarrollo Web en Entorno Servidor', 'idEstudio' => 1],
            ['nombre' => 'Despliegue de Aplicaciones Web', 'idEstudio' => 1],
            ['nombre' => 'Diseño de Interfaces Web', 'idEstudio' => 1],
            ['nombre' => 'Bases de Datos', 'idEstudio' => 1],
            ['nombre' => 'Programación', 'idEstudio' => 2],
            ['nombre' => 'Acceso a Datos', 'idEstudio' => 2],
            ['nombre' => 'Desarrollo de Interfaces', 'idEstudio' => 2],
            ['nombre' => 'Sistemas de Gestión Empresarial', 'idEstudio' => 2],
            ['nombre' => 'Administración de Sistemas Operativos', 'idEstudio' => 3],
            ['nombre' => 'Servicios de Red e Internet', 'idEstudio' => 3],
            ['nombre' => 'Implantación de Aplicaciones Web', 'idEstudio' => 3],
            ['nombre' => 'Seguridad y Alta Disponibilidad', 'idEstudio' => 3],
        ]);
    }
}
