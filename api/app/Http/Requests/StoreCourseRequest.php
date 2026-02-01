<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Course;

class StoreCourseRequest extends FormRequest
{
    // 1. Autorización: Permitir que quien tenga token pase (ya lo filtra el middleware de rutas)
    public function authorize()
    {
        return true; 
    }

    // 2. Reglas Básicas
    public function rules()
    {
        return [
            'period_id' => 'required|exists:periods,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week' => 'required|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time', // Fin debe ser después de Inicio
            'max_quota' => 'required|integer|min:1',
        ];
    }

    // 3. "Validación Inteligente" (Aquí evitamos los choques)
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Buscamos si el PROFE ya tiene clase ese día a esa hora
            $overlap = Course::where('teacher_id', $this->teacher_id)
                ->where('period_id', $this->period_id) // En el mismo periodo
                ->where('day_of_week', $this->day_of_week)
                ->where(function ($query) {
                    // Lógica de solapamiento de horarios
                    $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                          ->orWhereBetween('end_time', [$this->start_time, $this->end_time]);
                })
                ->exists();

            if ($overlap) {
                $validator->errors()->add('teacher_id', 'El profesor ya tiene una clase asignada en este horario.');
            }
        });
    }
}