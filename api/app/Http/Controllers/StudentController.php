<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CourseResource;

class StudentController extends Controller
{
    // Ver KARDEX (Historial)
    public function myKardex(Request $request)
    {
        $student = $request->user()->student; // Relación desde User

        $kardex = Enrollment::with(['course.subject', 'course.period'])
                    ->where('student_id', $student->id)
                    ->get();

        // Puedes crear un EnrollmentResource para formatear esto mejor
        return response()->json($kardex);
    }

    // INSCRIBIRSE A UNA MATERIA
    public function enroll(Request $request)
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);

        $student = $request->user()->student;
        $courseId = $request->course_id;

        try {
            DB::transaction(function () use ($student, $courseId) {
                // 1. Bloquear registro para lectura (LockForUpdate) para evitar condiciones de carrera en el cupo
                $course = Course::lockForUpdate()->find($courseId);

                // 2. Validar Cupo
                if ($course->current_quota >= $course->max_quota) {
                    throw new \Exception('El grupo está lleno.');
                }

                // 3. Validar si ya está inscrito
                $exists = Enrollment::where('student_id', $student->id)
                                    ->where('course_id', $courseId)
                                    ->exists();
                if ($exists) {
                    throw new \Exception('Ya estás inscrito en esta materia.');
                }

                // 4. Crear Inscripción
                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                ]);

                // 5. Actualizar Cupo
                $course->increment('current_quota');
            });

            return response()->json(['message' => 'Inscripción exitosa'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    
    // Ver Pagos
    public function myPayments(Request $request) {
        $payments = $request->user()->student->payments;
        return response()->json($payments);
    }
}