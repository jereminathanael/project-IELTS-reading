<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->user()->role !== $role) {
            return response()->json([
                    'message' => 'Forbidden',
                    'user_role' => $request->user()->role,  // cek role di db
                    'required_role' => $role,                // cek role yang diminta route
                ], 403);    
            }

        return $next($request);
    }
}
