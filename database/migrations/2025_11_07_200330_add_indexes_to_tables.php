<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade índices para optimizar queries:
     * - Foreign keys (idEstudio, idAlumno, idAsignatura)
     * - Campos usados para ordenación (nombre)
     */
    public function up(): void
    {
        // Índices en tabla estudio
        Schema::table('estudio', function (Blueprint $table) {
            $table->index('nombre', 'idx_estudio_nombre');
        });

        // Índices en tabla asignatura
        Schema::table('asignatura', function (Blueprint $table) {
            $table->index('idEstudio', 'idx_asignatura_idEstudio');
            $table->index('nombre', 'idx_asignatura_nombre');
        });

        // Índices en tabla alumno
        Schema::table('alumno', function (Blueprint $table) {
            $table->index('nombre', 'idx_alumno_nombre');
        });

        // Índices en tabla alumno_asignatura (tabla pivot)
        Schema::table('alumno_asignatura', function (Blueprint $table) {
            $table->index('idAlumno', 'idx_alumno_asignatura_idAlumno');
            $table->index('idAsignatura', 'idx_alumno_asignatura_idAsignatura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudio', function (Blueprint $table) {
            $table->dropIndex('idx_estudio_nombre');
        });

        Schema::table('asignatura', function (Blueprint $table) {
            $table->dropIndex('idx_asignatura_idEstudio');
            $table->dropIndex('idx_asignatura_nombre');
        });

        Schema::table('alumno', function (Blueprint $table) {
            $table->dropIndex('idx_alumno_nombre');
        });

        Schema::table('alumno_asignatura', function (Blueprint $table) {
            $table->dropIndex('idx_alumno_asignatura_idAlumno');
            $table->dropIndex('idx_alumno_asignatura_idAsignatura');
        });
    }
};
