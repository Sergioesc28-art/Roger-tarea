<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'rol' => $this->role->name, // Aquí accedemos al nombre del rol, no al ID
            'estatus' => $this->active ? 'Activo' : 'Inactivo',
            
            // Esto es MAGIA: Solo muestra el perfil de alumno si existe la relación cargada
            'perfil_alumno' => $this->when($this->student, function () {
                return [
                    'matricula' => $this->student->enrollment_number,
                    'nombre' => $this->student->first_name . ' ' . $this->student->last_name,
                    'carrera' => $this->student->career->name ?? 'Sin carrera asignada',
                ];
            }),

            // Lo mismo para el docente
            'perfil_docente' => $this->when($this->teacher, function () {
                return [
                    'nombre' => $this->teacher->first_name . ' ' . $this->teacher->last_name,
                    'rfc' => $this->teacher->rfc,
                ];
            }),
        ];
    }
}