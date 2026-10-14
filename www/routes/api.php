<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias('check.role', CheckRole::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->reportable(fn (\Throwable $e) => true)
            ->renderable(fn (\Throwable $e, \Illuminate\Http\Request $request) => response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500));
    })
    ->create();