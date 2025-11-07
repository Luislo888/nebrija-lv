<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumno_asignatura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idAlumno');
            $table->unsignedBigInteger('idAsignatura');
            $table->timestamps();

            $table->foreign('idAlumno')
                ->references('id')
                ->on('alumno')
                ->onDelete('cascade');

            $table->foreign('idAsignatura')
                ->references('id')
                ->on('asignatura')
                ->onDelete('cascade');

            $table->index('idAlumno');
            $table->index('idAsignatura');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumno_asignatura');
    }
};
