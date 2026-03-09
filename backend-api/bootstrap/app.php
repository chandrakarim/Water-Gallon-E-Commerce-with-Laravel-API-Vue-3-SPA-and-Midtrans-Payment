<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Helpers\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        // penting: midtrans callback tidak kena csrf
        $middleware->validateCsrfTokens(except: [
            'midtrans/*',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {

        // VALIDATION
        $exceptions->render(function (ValidationException $e, $request) {
            if (!$request || !$request->is('api/*')) return null;

            return ApiResponse::error('Validasi gagal', $e->errors(), 422);
        });

        // AUTHENTICATION (token salah / expired)
        $exceptions->render(function (AuthenticationException $e, $request) {
            if (!$request || !$request->is('api/*')) return null;

            return ApiResponse::error('Unauthorized', null, 401);
        });

        // 404
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if (!$request || !$request->is('api/*')) return null;

            return ApiResponse::error('Endpoint tidak ditemukan', null, 404);
        });

        // 403 (policy)
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            if (!$request || !$request->is('api/*')) return null;

            return ApiResponse::error('Tidak punya akses', null, 403);
        });

        // 500 (server error)
        $exceptions->render(function (\Throwable $e, $request) {
            if (!$request || !$request->is('api/*')) return null;

            \Log::error($e);

            return ApiResponse::error(
                config('app.debug') ? $e->getMessage() : 'Server error',
                null,
                500
            );
        });
    })

    ->create();