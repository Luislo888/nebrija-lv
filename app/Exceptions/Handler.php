<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /* @var array<int, string> */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /* Registrar los callbacks de manejo de excepciones para la aplicación. */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /* Renderizar una excepción en una respuesta HTTP. */
    public function render($request, Throwable $exception)
    {
        // Si es una excepción de validación y es una petición a API, devolver JSON
        if ($exception instanceof ValidationException) {
            if (strpos($request->getPathInfo(), '/api/') === 0) {
                return response()->json([
                    'error' => 'Error de validación',
                    'message' => 'Los parámetros enviados no son válidos',
                    'errors' => $exception->errors()
                ], 422, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return parent::render($request, $exception);
    }
}
