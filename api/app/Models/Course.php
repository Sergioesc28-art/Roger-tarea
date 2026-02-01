<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    // Aunque la tabla se llame 'courses', Laravel lo asume.
    // Si usaste 'clases' en español, agrega: protected $table = 'clases';

    protected $fillable = [
        'period_id', 'subject_id', 'teacher_id', 'classroom_id',
        'group_name', 'day_of_week', 'start_time', 'end_time',
        'max_quota', 'current_quota'
    ];

    // Relaciones para saber "Quién, Qué, Dónde y Cuándo"
    public function period() { return $this->belongsTo(Period::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function classroom() { return $this->belongsTo(Classroom::class); }

    // Un curso tiene muchos alumnos inscritos
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}