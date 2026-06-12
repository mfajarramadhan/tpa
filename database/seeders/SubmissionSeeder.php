<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Material::with('subject.classroom')
            ->where('is_task', true)
            ->get();

        $filePath = 'submissions/jawaban.pdf';

        $driveLink = 'https://drive.google.com/file/d/1B9YTlWzAZ539cuZXXUyoHT-mvV8nopCN/view?usp=sharing';

        $statuses = [
            'selesai',
            'selesai',
            'selesai',
            'perbaiki',
            'terkirim',
        ];

        foreach ($tasks as $task) {

            $classroom = $task->subject->classroom;

            if (!$classroom) {
                continue;
            }

            $students = Student::where('classroom_id', $classroom->id)
                ->where('status', 'aktif')
                ->inRandomOrder()
                ->limit(rand(1, 3))
                ->get();

            foreach ($students as $student) {

                $status = $statuses[array_rand($statuses)];

                $useLink = rand(1, 10) <= 3;

                Submission::updateOrCreate(
                    [
                        'material_id' => $task->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'status' => $status,
                        'file_path' => $useLink ? null : $filePath,
                        'link' => $useLink ? $driveLink : null,
                        'note' => $status === 'perbaiki'
                            ? 'Salah upload file tugas!'
                            : null,
                    ]
                );
            }
        }
    }
}