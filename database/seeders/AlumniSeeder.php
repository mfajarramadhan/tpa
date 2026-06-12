<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        $classroom = Classroom::where('name', 'DTA 4')->first();

        $parents = [
            [
                'name' => 'Enok Komariah',
                'phone' => '081287654321',
                'email' => 'enokkomariah@gmail.com',
                'password' => 'enokkomariah12345',
                'address' => 'Perum Palumbonsari Blok E2 No. 9, Karawang',
            ],
            [
                'name' => 'Neni Nuraeni',
                'phone' => '085712345678',
                'email' => 'neninuraeni@gmail.com',
                'password' => 'neninuraeni12345',
                'address' => 'Jl. Lamaran Gang Mawar No. 12, Palumbonsari',
            ],
        ];

        foreach ($parents as $parent) {
            $user = User::firstOrCreate(
                ['email' => $parent['email']],
                [
                    'name' => $parent['name'],
                    'phone' => $parent['phone'],
                    'password' => Hash::make($parent['password']),
                    'status' => 'aktif',
                    'approval_status' => 'approved',
                    'address' => $parent['address'],
                    'role' => 'orang_tua',
                ]
            );

            $user->assignRole('orang_tua');
        }

        $parentEnok = User::where('email', 'enokkomariah@gmail.com')->first();
        $parentNeni = User::where('email', 'neninuraeni@gmail.com')->first();

        $alumniData = [
            [
                'name' => 'Ahmad Fauzan',
                'nisn' => '3275063001',
                'parent_id' => $parentEnok?->id,
                'birth_date' => Carbon::now()->subYears(15)->format('Y-m-d'),
                'gender' => 'L',
            ],
            [
                'name' => 'Muhammad Jamal',
                'nisn' => '3275063002',
                'parent_id' => $parentEnok?->id,
                'birth_date' => Carbon::now()->subYears(15)->format('Y-m-d'),
                'gender' => 'L',
            ],
            [
                'name' => 'Salsabila Putri',
                'nisn' => '3275063003',
                'parent_id' => $parentNeni?->id,
                'birth_date' => Carbon::now()->subYears(14)->format('Y-m-d'),
                'gender' => 'P',
            ],
            [
                'name' => 'Nurul Aulia',
                'nisn' => '3275063004',
                'parent_id' => $parentNeni?->id,
                'birth_date' => Carbon::now()->subYears(14)->format('Y-m-d'),
                'gender' => 'P',
            ],
        ];

        foreach ($alumniData as $data) {
            $birthDate = Carbon::parse($data['birth_date'])->format('dmY');

            $emailName = strtolower(
                str_replace([' ', '.', "'"], '', $data['name'])
            );

            $email = $emailName . $birthDate . '@gmail.com';

            $studentUser = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'phone' => null,
                    'password' => Hash::make($birthDate),
                    'status' => 'nonaktif',
                    'approval_status' => 'approved',
                    'address' => 'Karawang',
                    'role' => 'siswa',
                ]
            );

            $studentUser->assignRole('siswa');

            Student::firstOrCreate(
                ['nisn' => $data['nisn']],
                [
                    'user_id' => $studentUser->id,
                    'parent_id' => $data['parent_id'],
                    'classroom_id' => null,
                    'academic_year_id' => $academicYear?->id,
                    'name' => $data['name'],
                    'birth_date' => $data['birth_date'],
                    'gender' => $data['gender'],
                    'school_origin' => 'SDN Palumbonsari 1',
                    'school_grade' => '6 SD',
                    'kk_file' => 'kk/kk.jpeg',
                    'birth_certificate_file' => 'akta/akta.jpeg',
                    'status' => 'alumni',
                    'approved_at' => now()->subYears(2),
                    'reject_reason' => null,
                ]
            );
        }
    }
}
