<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Period;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;

class TeacherController extends Controller
{
    // Ver mis clases y horarios
    public function mySchedule(Request $request)
    {
        $teacher = $request->user()->teacher;
        $periodoActivo = Period::where('status', 'Activo')->first();

        if (!$periodoActivo) {
            return response()->json(['message' => 'No hay periodo activo'], 404);
        }

        // Traer cursos donde el teacher_id sea el mío
        $courses = \App\Models\Course::with(['subject', 'classroom'])
                    ->where('teacher_id', $teacher->id)
                    ->where('period_id', $periodoActivo->id)
                    ->get();

        return CourseResource::collection($courses);
    }

    // Subir Calificaciones
    public function updateGrade(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grade' => 'required|numeric|min:0|max:10',
            'type' => 'required|in:p1,p2,final' // Parcial 1, 2 o Final
        ]);

        $teacher = $request->user()->teacher;

        // Validar seguridad: ¿El alumno realmente está en una clase de ESTE profesor?
        $enrollment = Enrollment::with('course')->find($request->enrollment_id);

        if ($enrollment->course->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'No tienes permiso para calificar a este alumno'], 403);
        }

        // Actualizar calificación dinámica
        if ($request->type === 'p1') $enrollment->grade_p1 = $request->grade;
        if ($request->type === 'p2') $enrollment->grade_p2 = $request->grade;
        if ($request->type === 'final') $enrollment->final_grade = $request->grade;

        $enrollment->save();

        return response()->json(['message' => 'Calificación actualizada']);
    }

    public function getCourseStudents(Request $request, $courseId)
    {
        $teacher = $request->user()->teacher;

        // 1. Validar que la materia pertenezca al maestro
        $course = Course::where('id', $courseId)
                        ->where('teacher_id', $teacher->id)
                        ->firstOrFail();

        // 2. Obtener inscripciones con datos del alumno
        // Usamos paginación porque un grupo puede ser grande
        $enrollments = Enrollment::with(['student.user'])
            ->where('course_id', $courseId)
            ->join('students', 'enrollments.student_id', '=', 'students.id') // Join para ordenar por apellido
            ->orderBy('students.last_name', 'asc')
            ->select('enrollments.*') // Evitar conflictos de ID en el join
            ->paginate(20);

        // 3. Formatear para la tabla de calificaciones
        $enrollments->through(function ($enrollment) {
            return [
                'enrollment_id' => $enrollment->id,
                'student_name' => $enrollment->student->last_name . ' ' . $enrollment->student->first_name,
                'matricula' => $enrollment->student->enrollment_number,
                'grades' => [
                    'p1' => $enrollment->grade_p1,
                    'p2' => $enrollment->grade_p2,
                    'final' => $enrollment->final_grade,
                ],
                'average' => $this->calculateAverage($enrollment) // Helper opcional
            ];
        });

        return response()->json([
            'course_info' => [
                'name' => $course->subject->name ?? 'Materia',
                'group' => $course->group_code ?? 'A'
            ],
            'students' => $enrollments
        ]);
    }

    private function calculateAverage($enrollment) {
        // Lógica simple de promedio, ajusta según tus reglas de negocio
        $sum = ($enrollment->grade_p1 ?? 0) + ($enrollment->grade_p2 ?? 0) + ($enrollment->final_grade ?? 0);
        // Esto es solo un ejemplo, depende de cómo ponderes
        return $sum > 0 ? round($sum / 3, 1) : 0;
    }
}