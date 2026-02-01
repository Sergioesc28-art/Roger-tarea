<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_clase' => $this->id,
            'nombre_materia' => $this->subject->name, // Accedemos a la relación Subject
            'creditos' => $this->subject->credits,
            
            'profesor' => $this->teacher->first_name . ' ' . $this->teacher->last_name,
            
            'salon' => $this->classroom->code,
            'tipo_salon' => $this->classroom->type,
            
            'horario' => [
                'dia' => $this->day_of_week,
                'inicio' => substr($this->start_time, 0, 5), // Formatea 14:00:00 a 14:00
                'fin' => substr($this->end_time, 0, 5),
            ],
            
            'cupo' => [
                'maximo' => $this->max_quota,
                'actual' => $this->current_quota,
                'disponible' => $this->max_quota - $this->current_quota,
            ],
            
            'periodo' => $this->period->code, // Ej: '2026-ENE-ABR'
        ];
    }
}