<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'sortBy' => 'sometimes|in:id,nombre',
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
            'page.integer' => 'El parámetro page debe ser numérico',
            'page.min' => 'El parámetro page debe ser mayor que 0',
            'limit.integer' => 'El parámetro limit debe ser numérico',
            'limit.min' => 'El parámetro limit debe ser mayor que 0',
            'limit.max' => 'El parámetro limit no puede exceder 100',
            'sortBy.in' => 'El campo sortBy no es válido',
            'sortOrder.in' => 'El valor sortOrder debe ser ASC o DESC',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }
}
