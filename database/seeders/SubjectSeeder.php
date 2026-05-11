<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjectsPerDay = [

            // Senin
            1 => [
                'Fiqih',
                'SKI',
            ],

            // Selasa
            2 => [
                'Aqidah',
                'Akhlak',
            ],

            // Rabu
            3 => [
                'Bahasa Arab',
                'Al-Qur\'an',
            ],

            // Kamis
            4 => [
                'Hadist',
                'Safinah',
            ],

            // Jumat
            5 => [
                'Kaligrafi',
                'Tahfidz Juz 30',
            ],

        ];

        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {

            foreach ($subjectsPerDay as $day => $subjects) {

                foreach ($subjects as $subjectName) {

                    Subject::create([
                        'classroom_id' => $classroom->id,
                        'name' => $subjectName,
                        'day' => $day,
                    ]);

                }

            }

        }
    }
}