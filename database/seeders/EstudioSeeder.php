<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estudio')->insert([
            ['nombre' => 'Desarrollo de Aplicaciones Web'],
            ['nombre' => 'Desarrollo de Aplicaciones Multiplataforma'],
            ['nombre' => 'Administración de Sistemas Informáticos en Red'],
        ]);
    }
}
