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
            'Fiqih',
            'SKI',
            'Aqidah',
            'Akhlak',
            'BHS Arab',
            'Al Qur\'an',
            'Hadist',
            'Akhlakul Badin',
            'Safinah',
            'Kaligrafi',
            'Tahfidz Juz 30'
        ];

        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {

            // ambil random 5 mapel
            $randomSubjects = collect($mapelList)
                ->shuffle()
                ->take(5)
                ->values();

            foreach ($randomSubjects as $index => $subject) {

                Subject::create([
                    'classroom_id' => $classroom->id,
                    'name' => $subject,

                    // 1-5 = senin-jumat
                    'day' => $index + 1
                ]);
            }
        }
    }
}