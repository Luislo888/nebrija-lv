<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EstudioController;
use App\Http\Controllers\Api\AsignaturaController;
use App\Http\Controllers\Api\AlumnoController;
use App\Http\Controllers\Api\MatriculacionController;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/estudios', [EstudioController::class, 'index']);
    Route::get('/asignaturas', [AsignaturaController::class, 'index']);
    Route::get('/alumnos', [AlumnoController::class, 'index']);
    Route::get('/alumnos-asignaturas', [MatriculacionController::class, 'index']);
});
