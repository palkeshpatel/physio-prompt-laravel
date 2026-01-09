<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $user = $request->user();

        // If Sanctum didn't resolve the user, manually resolve Admin from token
        if (!$user || !($user instanceof Admin)) {
            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);

                if ($accessToken && $accessToken->tokenable_type === Admin::class) {
                    $admin = $accessToken->tokenable;

                    if ($admin instanceof Admin) {
                        $request->setUserResolver(function () use ($admin) {
                            return $admin;
                        });
                        $user = $admin;
                    }
                }
            }
        }

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!($user instanceof Admin)) {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        return $next($request);
    }
}
