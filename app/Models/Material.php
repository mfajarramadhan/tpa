<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'user_id',
        'title',
        'description',
        'file_path',
        'youtube_link',
        'is_task',
    ];

    public function class()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function mySubmission()
    {
        return $this->hasOne(Submission::class)
            ->where('student_id', auth()->user()->student->id ?? 0);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
