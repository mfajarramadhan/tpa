<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'user_id',
        'class_id',
        'nik',
        'name',
        'birth_date',
        'gender',
        'address',
        'status'
    ];

    // 🔹 Relasi ke orang tua
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // 🔹 Relasi ke akun siswa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Relasi ke kelas
    public function class()
    {
        return $this->belongsTo(Classroom::class);
    }

    // 🔹 Absensi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // 🔹 Pembayaran
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // 🔹 Submission tugas
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    // 🔹 Parse tanggal lahir untuk umur
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
