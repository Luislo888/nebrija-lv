<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlumnoRequest;
use App\Http\Resources\AlumnoCollection;
use App\Models\Alumno;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    /**
     * Obtener alumnos con paginación y ordenación
     * 
     * @param AlumnoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AlumnoRequest $request)
    {
        try {
            $validated = $request->validated();

            $limit = $validated['limit'] ?? 10;
            $sortBy = $validated['sortBy'] ?? 'nombre';
            $sortOrder = $validated['sortOrder'] ?? 'ASC';

            $alumnos = Alumno::orderBy($sortBy, $sortOrder)
                ->paginate($limit);

            return new AlumnoCollection($alumnos);
        } catch (\Exception $e) {
            Log::error('Error al obtener alumnos: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al obtener alumnos',
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}
