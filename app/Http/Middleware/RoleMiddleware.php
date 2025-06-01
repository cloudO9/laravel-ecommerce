<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Works for both web and API routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Role name passed from route middleware
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== $role) {
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $user 
                        ? "Access denied. This endpoint requires '{$role}' role, but you are a '{$user->role}'."
                        : 'Authentication required',
                    'error' => $user ? 'insufficient_permissions' : 'unauthenticated',
                    'required_role' => $role,
                    'user_role' => $user ? $user->role : null
                ], $user ? 403 : 401);
            }
            
            // For web requests, use the original behavior
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}