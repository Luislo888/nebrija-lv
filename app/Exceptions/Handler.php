<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
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
