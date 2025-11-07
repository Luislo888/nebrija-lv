<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatriculacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alumnoId' => $this->alumnoId,
            'alumnoNombre' => $this->alumnoNombre,
            'asignaturaId' => $this->asignaturaId,
            'asignaturaNombre' => $this->asignaturaNombre,
            'estudioId' => $this->estudioId,
            'estudioNombre' => $this->estudioNombre,
        ];
    }
}
