<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EstudioResource;
use App\Models\Estudio;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EstudioController extends Controller
{
    /**
     * Obtener todos los estudios ordenados por nombre
     * Los resultados se cachean por 1 hora ya que los estudios cambian poco
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            // Cachear estudios por 1 hora (3600 segundos)
            $estudios = Cache::remember('estudios.all', 3600, function () {
                return Estudio::orderBy('nombre', 'ASC')->get();
            });

            return EstudioResource::collection($estudios);
        } catch (\Exception $e) {
            Log::error('Error al obtener estudios: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error al obtener estudios',
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}
