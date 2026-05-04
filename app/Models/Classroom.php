<?php

namespace App\Models;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function materials()
    {
        return $this->hasManyThrough(
            Material::class,
            Subject::class
        );
    }
}

