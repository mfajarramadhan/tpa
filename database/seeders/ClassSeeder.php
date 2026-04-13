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

        for ($i = 1; $i <= 6; $i++) {
            Classroom::firstOrCreate([
                'name' => 'Kelas ' . $i,
                'academic_year' => $tahunAjaran
            ]);
        }
    }
}
