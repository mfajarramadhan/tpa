<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\AssignmentSubmissionFactory> */
    use HasFactory;
    protected $fillable = [
        'student_id',
        'assignment_id',
        'file_path'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
