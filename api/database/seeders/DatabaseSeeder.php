<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Career;
use App\Models\Period;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Roles (Vital para el Middleware)
        $roleAdmin = Role::create(['name' => 'admin', 'description' => 'Administrador Total']);
        $roleDocente = Role::create(['name' => 'docente', 'description' => 'Profesor']);
        $roleAlumno = Role::create(['name' => 'alumno', 'description' => 'Estudiante']);

        // 2. Crear el PRIMER USUARIO (Tu llave maestra)
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('password123'), // Contraseña segura
            'role_id' => $roleAdmin->id,
            'active' => true,
        ]);

        // 3. Crear Catálogos Básicos (Para probar)
        Career::create(['name' => 'Ingeniería en Software', 'duration_quarters' => 10]);
        Career::create(['name' => 'Licenciatura en Derecho', 'duration_quarters' => 9]);

        Period::create([
            'code' => '2026-ENE-ABR',
            'start_date' => '2026-01-01',
            'end_date' => '2026-04-30',
            'status' => 'Activo' // Importante para tus Scopes
        ]);

        Classroom::create(['code' => 'LAB-1', 'capacity' => 20, 'type' => 'Laboratorio']);
        Classroom::create(['code' => 'AULA-101', 'capacity' => 30, 'type' => 'Aula']);
    }
}