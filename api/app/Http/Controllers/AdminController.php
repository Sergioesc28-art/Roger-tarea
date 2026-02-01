<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // === REGISTRO DE ALUMNOS (Transaccional) ===
    public function registerStudent(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'matricula' => 'required|unique:students,enrollment_number',
            'curp' => 'required|unique:students,curp',
            'first_name' => 'required',
            'last_name' => 'required',
            'career_id' => 'required|exists:careers,id',
            'birth_date' => 'required|date'
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                // 1. Crear Usuario
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => 3, // ID 3 = Alumno (según tu DB seed)
                    'active' => true,
                ]);

                // 2. Crear Perfil
                $student = Student::create([
                    'user_id' => $user->id,
                    'career_id' => $request->career_id,
                    'enrollment_number' => $request->matricula,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'curp' => $request->curp,
                    'birth_date' => $request->birth_date,
                    'current_quarter' => 1
                ]);

                return $student;
            });

            return response()->json(['message' => 'Alumno registrado con éxito', 'id' => $result->id], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en el registro: ' . $e->getMessage()], 500);
        }
    }

    // === REGISTRO DE DOCENTES (Transaccional) ===
    public function registerTeacher(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'rfc' => 'required|unique:teachers,rfc'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => 2, // ID 2 = Docente
                    'active' => true,
                ]);

                Teacher::create([
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'rfc' => $request->rfc,
                    'phone_number' => $request->phone_number ?? null,
                ]);
            });

            return response()->json(['message' => 'Docente registrado con éxito'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}