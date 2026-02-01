<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 
        'rfc', 'professional_license', 'phone_number'
    ];

    // Relación Inversa con Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un maestro imparte muchas clases (cursos)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}