<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'error' => 'Token tidak valid atau tidak ada'
            ], 401);
        }

        $user = auth()->user();
        
        if (!$user->hasAnyRole($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
                'error' => 'Anda tidak memiliki akses untuk endpoint ini',
                'required_roles' => $roles,
                'user_roles' => $user->roles->pluck('name')
            ], 403);
        }

        return $next($request);
    }
}
