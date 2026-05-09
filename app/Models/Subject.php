<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'classroom_id',
        'name',
        'day'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function getDayNameAttribute()
    {
        return [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ][$this->day] ?? null;
    }

}