<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhysio
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user is authenticated and is a User model instance (not Admin)
        if (!$user || !($user instanceof \App\Models\User)) {
            return response()->json([
                'message' => 'Unauthorized. User access required.',
            ], 403);
        }

        return $next($request);
    }
}

