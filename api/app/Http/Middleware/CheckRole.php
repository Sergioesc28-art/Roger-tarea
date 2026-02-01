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
    public function handle(Request $request, Closure $next, $role)
    {
        // Mapeo de roles (ajusta los IDs según tu base de datos)
        // 1: Admin, 2: Docente, 3: Alumno
        $rolesMap = [
            'admin' => 1,
            'docente' => 2,
            'alumno' => 3
        ];

        if (!$request->user() || $request->user()->role_id !== $rolesMap[$role]) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return $next($request);
    }
}