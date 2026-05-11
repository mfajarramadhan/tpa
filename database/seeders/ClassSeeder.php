<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = '2025/2026';

        $classrooms = [
            'TPA/TPQ 1',
            'TPA/TPQ 2',
            'DTA 1',
            'DTA 2',
            'DTA 3',
            'DTA 4',
        ];

        foreach ($classrooms as $classroom) {

            Classroom::firstOrCreate([
                'name' => $classroom,
                'academic_year' => $tahunAjaran
            ]);

        }
    }
}
