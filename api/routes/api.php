<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importación de Controladores
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ========================================================================
// 1. RUTAS PÚBLICAS (No requieren Token)
// ========================================================================
Route::post('/login', [AuthController::class, 'login']);

// ========================================================================
// 2. RUTAS PROTEGIDAS (Requieren Token Bearer)
// ========================================================================
Route::middleware('auth:sanctum')->group(function () {

    // --- COMUNES PARA TODOS LOS USUARIOS ---
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Obtener usuario actual (útil para validar sesión en el frontend)
    Route::get('/me', function (Request $request) {
        return $request->user()->load(['role']); 
    });


    // ====================================================================
    // 3. ZONA ADMIN (Middleware: role:admin)
    // ====================================================================
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        // --- GESTIÓN DE ALUMNOS ---
        Route::get('/students', [AdminController::class, 'indexStudents']);           // Lista paginada
        Route::post('/register/student', [AdminController::class, 'registerStudent']); // Crear
        Route::put('/student/{id}', [AdminController::class, 'updateStudent']);        // Editar
        
        // --- GESTIÓN DE DOCENTES ---
        Route::get('/teachers', [AdminController::class, 'indexTeachers']);           // Lista paginada
        Route::post('/register/teacher', [AdminController::class, 'registerTeacher']); // Crear
        
        // --- GESTIÓN DE USUARIOS (General) ---
        Route::patch('/user/{id}/toggle', [AdminController::class, 'toggleUserStatus']); // Activar/Desactivar

        // --- GESTIÓN DE CLASES (Usando CourseController y AdminController) ---
        Route::get('/courses', [AdminController::class, 'indexCourses']);       // Lista paginada para la tabla
        Route::post('/courses', [CourseController::class, 'store']);            // Crear nueva clase
        Route::get('/courses/{id}', [CourseController::class, 'show']);         // Ver detalle
        Route::put('/courses/{id}', [CourseController::class, 'update']);       // Actualizar
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);   // Eliminar

        // --- CATÁLOGOS (Carreras y Materias) ---
        Route::get('/careers', [AdminController::class, 'indexCareers']);
        Route::post('/careers', [AdminController::class, 'storeCareer']);
        
        Route::get('/subjects', [AdminController::class, 'indexSubjects']);
        Route::post('/subjects', [AdminController::class, 'storeSubject']);
    });


    // ====================================================================
    // 4. ZONA DOCENTE (Middleware: role:docente)
    // ====================================================================
    Route::middleware(['role:docente'])->prefix('teacher')->group(function () {
        
        // Ver mi horario y clases asignadas
        Route::get('/schedule', [TeacherController::class, 'mySchedule']);
        
        // Obtener lista de alumnos de un curso específico (para calificar)
        Route::get('/course/{courseId}/students', [TeacherController::class, 'getCourseStudents']);
        
        // Asentar calificación (Parcial 1, Parcial 2 o Final)
        Route::post('/grade', [TeacherController::class, 'updateGrade']);
    });


    // ====================================================================
    // 5. ZONA ALUMNO (Middleware: role:alumno)
    // ====================================================================
    Route::middleware(['role:alumno'])->prefix('student')->group(function () {
        
        // Ver historial académico completo (Kardex)
        Route::get('/kardex', [StudentController::class, 'myKardex']);
        
        // Ver carga académica actual (Horario del periodo activo)
        Route::get('/schedule', [StudentController::class, 'myCurrentSchedule']);
        
        // Inscribirse a una materia
        Route::post('/enroll', [StudentController::class, 'enroll']);
        
        // Ver historial de pagos
        Route::get('/payments', [StudentController::class, 'myPayments']);
        
        // Actualizar datos de contacto
        Route::put('/profile', [StudentController::class, 'updateProfile']);
    });

});