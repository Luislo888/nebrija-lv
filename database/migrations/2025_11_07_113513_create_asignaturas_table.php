<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignatura', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->unsignedBigInteger('idEstudio');
            $table->timestamps();

            $table->foreign('idEstudio')
                ->references('id')
                ->on('estudio')
                ->onDelete('cascade');

            $table->index('idEstudio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignatura');
    }
};
