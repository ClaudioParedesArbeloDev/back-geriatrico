<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
   
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

       
        $user->loadMissing('roles');

        
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'No tenés permisos para realizar esta acción',
        ], 403);
    }
}
