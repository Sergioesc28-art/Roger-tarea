<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Period;
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
    
    public function myCurrentSchedule(Request $request)
    {
        $student = $request->user()->student;
        
        // Obtener periodo activo
        $activePeriod = Period::where('status', 'Activo')->first();
        if (!$activePeriod) {
            return response()->json(['message' => 'No hay periodo activo'], 404);
        }

        // Obtener materias inscritas SOLO del periodo actual
        // No necesitamos paginación aquí porque un alumno rara vez tiene más de 7-8 materias activas
        $schedule = Enrollment::with(['course.subject', 'course.teacher', 'course.classroom'])
            ->where('student_id', $student->id)
            ->whereHas('course', function($q) use ($activePeriod) {
                $q->where('period_id', $activePeriod->id);
            })
            ->get();

        // Formateo para renderizar el calendario en el Frontend
        $formatted = $schedule->map(function($item) {
            return [
                'course_id' => $item->course_id,
                'subject' => $item->course->subject->name,
                'teacher' => $item->course->teacher ? ($item->course->teacher->first_name . ' ' . $item->course->teacher->last_name) : 'TBD',
                'classroom' => $item->course->classroom->name ?? 'Virtual',
                // Asumiendo que guardas el horario en texto o JSON (ej: "Lun 8-10, Mie 8-10")
                'schedule_text' => $item->course->schedule_info, 
                // Si tienes los días desglosados en DB, retornar eso mejor
            ];
        });

        return response()->json($formatted);
    }

    // === ACTUALIZAR DATOS DE CONTACTO ===
    public function updateProfile(Request $request)
    {
        $student = $request->user()->student;
        
        // Generalmente al alumno solo se le deja cambiar teléfono o foto, no nombre ni matrícula
        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255'
        ]);

        $student->update([
            'phone' => $request->phone,
            'address' => $request->address // Asegúrate de tener este campo en la migración
        ]);

        return response()->json(['message' => 'Perfil actualizado']);
    }
}