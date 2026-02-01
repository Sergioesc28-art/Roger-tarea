<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificar si el usuario está logueado (aunque auth:sanctum ya lo hace)
        if (! $request->user()) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // 2. Verificar si el rol del usuario coincide con los roles permitidos en la ruta
        // $request->user()->role->name nos da 'admin', 'alumno', etc.
        if (! in_array($request->user()->role->name, $roles)) {
            return response()->json(['message' => 'No autorizado. Rol insuficiente.'], 403);
        }

        return $next($request);
    }
}