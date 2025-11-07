<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alumno')->insert([
            ['nombre' => 'Juan García López'],
            ['nombre' => 'María López Martínez'],
            ['nombre' => 'Carlos Martínez Ruiz'],
            ['nombre' => 'Ana Rodríguez Fernández'],
            ['nombre' => 'David Fernández González'],
            ['nombre' => 'Laura Sánchez Pérez'],
            ['nombre' => 'Pedro González Jiménez'],
            ['nombre' => 'Carmen Jiménez Moreno'],
            ['nombre' => 'Miguel Moreno Álvarez'],
            ['nombre' => 'Isabel Álvarez Romero'],
            ['nombre' => 'Francisco Romero Torres'],
            ['nombre' => 'Elena Torres Navarro'],
            ['nombre' => 'Antonio Navarro Gil'],
            ['nombre' => 'Rosa Gil Vázquez'],
            ['nombre' => 'José Vázquez Serrano'],
            ['nombre' => 'Lucía Serrano Castro'],
            ['nombre' => 'Manuel Castro Ortega'],
            ['nombre' => 'Patricia Ortega Rubio'],
            ['nombre' => 'Javier Rubio Molina'],
            ['nombre' => 'Cristina Molina Delgado'],
            ['nombre' => 'Sergio Delgado Ramírez'],
            ['nombre' => 'Beatriz Ramírez Suárez'],
            ['nombre' => 'Alberto Suárez Blanco'],
            ['nombre' => 'Silvia Blanco Pascual'],
            ['nombre' => 'Raúl Pascual Méndez'],
            ['nombre' => 'Marta Méndez Cruz'],
            ['nombre' => 'Fernando Cruz Herrera'],
            ['nombre' => 'Natalia Herrera Guerrero'],
            ['nombre' => 'Adrián Guerrero Cortés'],
            ['nombre' => 'Sofía Cortés Iglesias'],
            ['nombre' => 'Diego Iglesias Medina'],
            ['nombre' => 'Julia Medina Garrido'],
            ['nombre' => 'Víctor Garrido Santos'],
            ['nombre' => 'Andrea Santos Núñez'],
            ['nombre' => 'Alejandro Núñez Muñoz'],
            ['nombre' => 'Paula Muñoz Marín'],
            ['nombre' => 'Ángel Marín Vargas'],
            ['nombre' => 'Irene Vargas Campos'],
            ['nombre' => 'Daniel Campos Vega'],
            ['nombre' => 'Mónica Vega Calvo'],
            ['nombre' => 'Roberto Calvo Reyes'],
            ['nombre' => 'Claudia Reyes León'],
            ['nombre' => 'Luis León Ibáñez'],
            ['nombre' => 'Sara Ibáñez Prieto'],
            ['nombre' => 'Marcos Prieto Caballero'],
            ['nombre' => 'Eva Caballero Mora'],
            ['nombre' => 'Ricardo Mora Peña'],
            ['nombre' => 'Alicia Peña Cano'],
            ['nombre' => 'Rubén Cano Domínguez'],
            ['nombre' => 'Verónica Domínguez Santana'],
        ]);
    }
}
