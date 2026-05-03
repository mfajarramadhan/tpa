<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $mapelList = [
            'Iqro',
            'Tahfidz',
            'Tajwid',
            'Fiqih',
            'Aqidah',
            'Hadits',
            'Doa Harian'
        ];

        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {

            // 🔥 ambil random 5 mapel
            $randomSubjects = collect($mapelList)->shuffle()->take(5);

            foreach ($randomSubjects as $subject) {
                Subject::create([
                    'classroom_id' => $classroom->id,
                    'name' => $subject,
                    'description' => 'Materi ' . $subject
                ]);
            }
        }
    }
}