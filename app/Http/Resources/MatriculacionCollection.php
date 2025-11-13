<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MatriculacionCollection extends ResourceCollection
{
    /* @return array<int|string, mixed> */
    public function toArray(Request $request): array
    {
        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();

        return [
            'data' => MatriculacionResource::collection($this->collection),
            'page' => $currentPage,
            'limit' => $this->perPage(),
            'total' => $this->total(),
            'totalPages' => $lastPage,
            'hasNext' => $currentPage < $lastPage,
            'hasPrev' => $currentPage > 1,
        ];
    }

    /* Deshabilitar el envoltorio del array de recursos más externo. */
    public function withResponse($request, $response): void
    {
        $data = $response->getData(true);

        // Eliminar links y meta si existen
        unset($data['links']);
        unset($data['meta']);

        $response->setData($data);
    }
}
