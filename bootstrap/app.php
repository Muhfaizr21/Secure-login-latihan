<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * ------------------------------------------------------------------
         * GLOBAL MIDDLEWARE (opsional)
         * ------------------------------------------------------------------
         * Kalau nanti kamu butuh middleware global seperti CORS atau logging,
         * bisa ditaruh di sini.
         */

        // Aktifkan middleware Laravel Sanctum untuk semua route API
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        /**
         * Optional tambahan keamanan:
         * Aktifkan throttle API (batas request per menit).
         */
        $middleware->api(append: [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Bisa kamu isi nanti untuk custom error handler
    })
    ->create();
