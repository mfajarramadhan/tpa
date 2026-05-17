<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;
    protected $fillable = [
        'student_id',
        'classroom_id',
        'academic_year_id',
        'date',
        'session',
        'created_by'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function details()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function academicYear()
    {
        return $this->belongsTo(
            AcademicYear::class
        );
    }
}
