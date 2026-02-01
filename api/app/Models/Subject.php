<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = ['career_id', 'name', 'credits', 'suggested_quarter', 'active'];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}