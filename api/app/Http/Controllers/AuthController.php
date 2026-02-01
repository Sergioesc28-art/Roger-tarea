<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource; // Asumiendo que creaste el Resource

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Eager Loading: Traemos el rol y los perfiles de una vez para no consultar después
        $user = User::with(['role', 'student', 'teacher'])
                    ->where('email', $request->email)
                    ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        if (!$user->active) {
            return response()->json(['message' => 'Acceso denegado. Usuario inactivo.'], 403);
        }

        // Generamos el Token de Sanctum
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Bienvenido',
            'token' => $token,
            'user' => new UserResource($user), // Devolvemos datos limpios
        ]);
    }

    public function logout(Request $request)
    {
        // Revoca el token actual
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}