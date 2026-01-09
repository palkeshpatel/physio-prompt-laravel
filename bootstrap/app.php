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
        $middleware->alias([
            'physio' => \App\Http\Middleware\EnsurePhysio::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
        
        // CORS is handled automatically by Laravel when config/cors.php exists
        // No need to manually register HandleCors middleware
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
