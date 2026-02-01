<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Cualquiera entra)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Necesitan Token válido)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    // === COMUNES (Todos los logueados pueden hacer esto) ===
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function (\Illuminate\Http\Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    });

    // ====================================================================
    // 1. ZONA ADMINISTRADOR (Solo 'admin')
    // ====================================================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Gestión de Usuarios
        Route::post('/register/student', [AdminController::class, 'registerStudent']);
        Route::post('/register/teacher', [AdminController::class, 'registerTeacher']);
        
        // Gestión de Clases (CRUD)
        Route::post('/courses', [CourseController::class, 'store']); // Crear clase
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']); // Borrar clase
    });

    // ====================================================================
    // 2. ZONA DOCENTE (Solo 'docente')
    // ====================================================================
    Route::middleware(['role:docente'])->prefix('teacher')->group(function () {
        Route::get('/schedule', [TeacherController::class, 'mySchedule']); // Ver mi horario
        Route::post('/grades', [TeacherController::class, 'updateGrade']); // Subir calificación
    });

    // ====================================================================
    // 3. ZONA ALUMNO (Solo 'alumno')
    // ====================================================================
    Route::middleware(['role:alumno'])->prefix('student')->group(function () {
        Route::get('/kardex', [StudentController::class, 'myKardex']); // Historial
        Route::get('/payments', [StudentController::class, 'myPayments']); // Pagos
        Route::post('/enroll', [StudentController::class, 'enroll']); // Inscribirse
    });

    // ====================================================================
    // 4. RUTAS COMPARTIDAS (Varios roles)
    // ====================================================================
    
    // Ver lista de clases: Admin, Docente y Alumno necesitan verlas
    Route::middleware(['role:admin,docente,alumno'])->group(function () {
        Route::get('/courses', [CourseController::class, 'index']);
        Route::get('/courses/{id}', [CourseController::class, 'show']);
    });

});