<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AcademicYearSeeder::class,
            ClassSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            FeeSeeder::class,
            StudentSeeder::class,
            AlumniSeeder::class,
            TeacherSeeder::class,
            PaymentSeeder::class,
            SubjectSeeder::class,
            MaterialSeeder::class,
            SubmissionSeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
