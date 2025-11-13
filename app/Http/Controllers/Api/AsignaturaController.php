<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignaturaRequest;
use App\Http\Resources\AsignaturaResource;
use App\Models\Asignatura;
use Illuminate\Support\Facades\Log;

class AsignaturaController extends Controller
{
    /* @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse */
    public function index(AsignaturaRequest $request)
    {
        try {
            $validated = $request->validated();

            $idEstudio = $validated['idEstudio'];
            $sortBy = $validated['sortBy'] ?? 'nombre';
            $sortOrder = $validated['sortOrder'] ?? 'ASC';

            $asignaturas = Asignatura::where('idEstudio', $idEstudio)
                ->orderBy($sortBy, $sortOrder)
                ->get();

            return AsignaturaResource::collection($asignaturas);
        } catch (\Exception $e) {
            Log::error('Error al obtener asignaturas: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al obtener asignaturas',
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}
