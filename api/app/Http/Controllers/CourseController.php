<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Period;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest; // <--- Tu validador personalizado
use App\Http\Resources\CourseResource;    // <--- Tu transformador JSON

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Optimización N+1 con Eager Loading
        $query = Course::with(['subject', 'teacher', 'classroom', 'period']);

        // Filtro por periodo (si no envía, toma el activo)
        if ($request->has('period_id')) {
            $query->where('period_id', $request->period_id);
        } else {
            $periodoActivo = Period::where('status', 'Activo')->first();
            if($periodoActivo) {
                $query->where('period_id', $periodoActivo->id);
            }
        }

        return CourseResource::collection($query->get());
    }

    public function store(StoreCourseRequest $request)
    {
        // Si llega aquí, YA PASÓ la validación de choques en StoreCourseRequest
        $course = Course::create($request->validated());

        return response()->json([
            'message' => 'Clase creada correctamente',
            'data' => new CourseResource($course)
        ], 201);
    }

    public function show($id)
    {
        $course = Course::with(['enrollments.student'])->findOrFail($id);
        return new CourseResource($course);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        // Validación de Integridad
        if ($course->enrollments()->exists()) {
            return response()->json(['message' => 'No se puede borrar una clase con alumnos inscritos.'], 409);
        }

        $course->delete(); // Soft Delete
        return response()->json(['message' => 'Clase eliminada']);
    }
}