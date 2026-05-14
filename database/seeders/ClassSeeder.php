<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = [

            'TPA 1',
            'TPA 2',

            'DTA 1',
            'DTA 2',
            'DTA 3',
            'DTA 4',

        ];

        foreach ($classrooms as $classroom) {
            Classroom::firstOrCreate([
                'name' => $classroom,
            ]);
        }
    }
}