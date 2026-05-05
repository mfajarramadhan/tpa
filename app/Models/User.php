<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Student;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'status',
        'address',
        'approval_status',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔹 Orang tua → punya banyak anak
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    // 🔹 Jika user adalah siswa
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // 🔹 Guru/Admin → membuat absensi
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'created_by');
    }

    public function approvedPayments()
    {
        return $this->hasMany(Payment::class, 'approved_by');
    }
}
