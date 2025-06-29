<?php

use App\Http\Middleware\AddRequestKey;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->use([
            AddRequestKey::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $exceptions): JsonResponse {
            return response()->json([
                'result' => 'error',
                'message' => 'VALIDATION_ERROR',
                'errors' => $exceptions->errors(),
            ], 422);
        });

        $exceptions->render(function (NotFoundHttpException $exception): JsonResponse {
            return response()->json([
                'result' => 'error',
                'message' => 'NOT_FOUND',
            ], 404);
        });

        $exceptions->render(function (Throwable $exception): JsonResponse {
            return response()->json([
                'result' => 'error',
                'message' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        });
    })->create();
