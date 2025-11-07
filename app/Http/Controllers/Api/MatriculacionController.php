<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatriculacionRequest;
use App\Http\Resources\MatriculacionCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatriculacionController extends Controller
{
    /**
     * Obtener matriculaciones con paginación y ordenación
     *
     * OPTIMIZACIÓN: Usa Query Builder con JOINs en lugar de Eloquent para evitar N+1 queries
     * y mejorar el rendimiento al obtener datos de múltiples tablas relacionadas
     *
     * @param MatriculacionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(MatriculacionRequest $request)
    {
        try {
            $validated = $request->validated();

            $page = $validated['page'] ?? 1;
            $limit = $validated['limit'] ?? 10;
            $sortBy = $validated['sortBy'] ?? 'alumnoNombre';
            $sortOrder = $validated['sortOrder'] ?? 'ASC';

            $ordenSecundario = $this->obtenerOrdenSecundario($sortBy);

            $query = DB::table('alumno_asignatura as aa')
                ->join('alumno as al', 'aa.idAlumno', '=', 'al.id')
                ->join('asignatura as asig', 'aa.idAsignatura', '=', 'asig.id')
                ->join('estudio as est', 'asig.idEstudio', '=', 'est.id')
                ->select(
                    'aa.id',
                    'al.id as alumnoId',
                    'al.nombre as alumnoNombre',
                    'asig.id as asignaturaId',
                    'asig.nombre as asignaturaNombre',
                    'est.id as estudioId',
                    'est.nombre as estudioNombre'
                )
                ->orderBy($sortBy, $sortOrder);

            if (!empty($ordenSecundario)) {
                foreach ($ordenSecundario as $campo => $direccion) {
                    $query->orderBy($campo, $direccion);
                }
            }

            $totalRegistros = DB::table('alumno_asignatura')->count();
            $offset = ($page - 1) * $limit;

            $items = $query->offset($offset)->limit($limit)->get();

            $paginator = new LengthAwarePaginator(
                $items,
                $totalRegistros,
                $limit,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return new MatriculacionCollection($paginator);
        } catch (\Exception $e) {
            Log::error('Error al obtener matriculaciones: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al obtener matriculaciones',
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    private function obtenerOrdenSecundario(string $sortBy): array
    {
        $ordenSecundario = [];

        switch ($sortBy) {
            case 'id':
                $ordenSecundario = [
                    'alumnoNombre' => 'ASC',
                    'asignaturaNombre' => 'ASC',
                    'estudioNombre' => 'ASC'
                ];
                break;

            case 'alumnoId':
            case 'alumnoNombre':
                $ordenSecundario = [
                    'estudioNombre' => 'ASC',
                    'asignaturaNombre' => 'ASC'
                ];
                break;

            case 'asignaturaId':
            case 'asignaturaNombre':
                $ordenSecundario = [
                    'alumnoNombre' => 'ASC',
                    'estudioNombre' => 'ASC'
                ];
                break;

            case 'estudioId':
            case 'estudioNombre':
                $ordenSecundario = [
                    'alumnoNombre' => 'ASC',
                    'asignaturaNombre' => 'ASC'
                ];
                break;
        }

        return $ordenSecundario;
    }
}
