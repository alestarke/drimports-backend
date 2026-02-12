<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        //
    })

    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class
    ])

    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (\Throwable $e, Request $request) {

            // Verifique se o cliente quer uma resposta JSON
            if ($request->wantsJson() || $request->is('api/*')) {

                // --- 1. EXCEÇÕES ESPECÍFICAS PRIMEIRO ---

                // Erro de Validação (422)
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'error' => true,
                        'error_code' => 'VALIDATION_FAILED',
                        'message' => 'Os dados fornecidos são inválidos.',
                        'errors' => $e->errors(),
                    ], 422);
                }

                // Erro de Autenticação (401 - Não logado)
                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'error' => true,
                        'error_code' => 'UNAUTHENTICATED',
                        'message' => 'User not authenticated.'
                    ], 401);
                }

                // --- 2. VERIFICAÇÃO GENÉRICA (A SOLUÇÃO) ---

                // Pega o status code. Se não for uma exceção HTTP, é 500.
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                // Pega a mensagem
                $message = $e->getMessage();
                $errorCode = 'UNKNOWN_ERROR';

                // --- 3. TRADUÇÃO BASEADA NO STATUS CODE ---

                switch ($statusCode) {
                    case 403:
                        $errorCode = 'FORBIDDEN';
                        $message = 'You do not have permission to perform this action.';
                        break;
                    case 404:
                        $errorCode = 'NOT_FOUND';
                        $message = 'The requested resource was not found.';
                        break;
                    case 500:
                        $errorCode = 'SERVER_ERROR';
                        if (!config('app.debug')) {
                            $message = 'An internal server error occurred.';
                        }
                        break;
                }

                // --- 4. PREPARA A RESPOSTA FINAL ---

                $response = [
                    'error' => true,
                    'error_code' => $errorCode,
                    'message' => $message,
                ];

                // Adiciona debug info APENAS se for debug E for um erro 500
                if ($statusCode === 500 && config('app.debug')) {
                    $response['debug_message'] = $e->getMessage();
                    $response['debug_trace'] = $e->getTraceAsString();
                }

                return response()->json($response, $statusCode);
            }
        });
    })->create();
