<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignaturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idEstudio' => 'required|integer|min:1|exists:estudio,id',
            'sortBy' => 'sometimes|in:id,nombre,idEstudio',
            'sortOrder' => 'sometimes|in:ASC,DESC,asc,desc',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('sortOrder')) {
            $this->merge([
                'sortOrder' => strtoupper($this->sortOrder),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'idEstudio.required' => 'El parámetro idEstudio es requerido',
            'idEstudio.integer' => 'El parámetro idEstudio debe ser numérico',
            'idEstudio.min' => 'El parámetro idEstudio debe ser mayor que 0',
            'idEstudio.exists' => 'El estudio especificado no existe',
            'sortBy.in' => 'El campo sortBy no es válido',
            'sortOrder.in' => 'El valor sortOrder debe ser ASC o DESC',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }
}
