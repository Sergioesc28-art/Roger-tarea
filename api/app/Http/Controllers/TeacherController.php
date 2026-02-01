<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Period;
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
}