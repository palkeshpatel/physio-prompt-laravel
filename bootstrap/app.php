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
        
        // Configure authentication to return JSON for API routes instead of redirecting
        $middleware->redirectGuestsTo(function ($request) {
            // For API routes or JSON requests, return null to get JSON response instead of redirect
            if ($request->expectsJson() || $request->is('api/*')) {
                return null;
            }
            // For web routes, return null (no web login route exists)
            return null;
        });
        
        // CORS is handled automatically by Laravel when config/cors.php exists
        // No need to manually register HandleCors middleware
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle unauthenticated exceptions for API routes
        $exceptions->shouldRenderJsonWhen(function ($request, \Throwable $e) {
            // Return JSON for API routes or JSON requests
            if ($request->is('api/*') || $request->expectsJson()) {
                return true;
            }
            
            // Also return JSON for authentication exceptions
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return true;
            }
            
            return false;
        });
        
        // Configure unauthenticated handler to return JSON for API routes
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }
        });
    })->create();
