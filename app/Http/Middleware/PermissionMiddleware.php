<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'error' => 'Token tidak valid atau tidak ada'
            ], 401);
        }

        $user = auth()->user();
        
        if (!$user->hasAnyPermission($permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
                'error' => 'Anda tidak memiliki permission untuk aksi ini',
                'required_permissions' => $permissions,
                'user_permissions' => $user->getAllPermissions()->pluck('name')
            ], 403);
        }

        return $next($request);
    }
}
