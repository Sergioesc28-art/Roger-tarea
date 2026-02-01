<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'career_id', 'enrollment_number', 
        'curp', 'first_name', 'last_name', 
        'birth_date', 'address', 'emergency_phone', 'current_quarter'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    // Un alumno tiene muchas inscripciones
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Un alumno tiene muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}