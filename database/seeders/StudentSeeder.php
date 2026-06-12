<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $classrooms = [
            'TPA 1' => Classroom::where('name', 'TPA 1')->first(),
            'TPA 2' => Classroom::where('name', 'TPA 2')->first(),
            'DTA 1' => Classroom::where('name', 'DTA 1')->first(),
            'DTA 2' => Classroom::where('name', 'DTA 2')->first(),
            'DTA 3' => Classroom::where('name', 'DTA 3')->first(),
            'DTA 4' => Classroom::where('name', 'DTA 4')->first(),
        ];

        $parents = [
            'Sarah Kartini' => User::where('email', 'sarahkartini@gmail.com')->first(),
            'Muhammad Fajar Ramadhan' => User::where('email', 'muhammadfajar@gmail.com')->first(),
            'Siti Nurjanah' => User::where('email', 'sitinurjanah@gmail.com')->first(),
            'Dewi Lestari' => User::where('email', 'dewilestari@gmail.com')->first(),
            'Yayah Rohayati' => User::where('email', 'yayahrohayati@gmail.com')->first(),
            'Nur Aisyah' => User::where('email', 'nuraisyah@gmail.com')->first(),
            'Rina Marlina' => User::where('email', 'rinamarlina@gmail.com')->first(),
            'Tati Hartati' => User::where('email', 'tatihartati@gmail.com')->first(),
            'Sulastri' => User::where('email', 'sulastri@gmail.com')->first(),
            'Yulianti' => User::where('email', 'yulianti@gmail.com')->first(),
        ];

        $studentsData = [

            // TPA 1
            [
                'name' => 'Aditiya Alfarizqy',
                'nisn' => '3275062001',
                'parent' => 'Yayah Rohayati',
                'classroom' => 'TPA 1',
                'birth_date' => Carbon::now()->subYears(8)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 1',
                'school_grade' => '2 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(4),
            ],

            [
                'name' => 'Dimas Aditya Haidar',
                'nisn' => '3275062002',
                'parent' => 'Yayah Rohayati',
                'classroom' => 'TPA 1',
                'birth_date' => Carbon::now()->subYears(8)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 2',
                'school_grade' => '2 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(2),
            ],

            [
                'name' => 'Miftahul Jannah',
                'nisn' => '3275062003',
                'parent' => 'Yayah Rohayati',
                'classroom' => 'TPA 1',
                'birth_date' => Carbon::now()->subYears(8)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 1',
                'school_grade' => '2 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ],

            [
                'name' => 'Arjuna Maulana',
                'nisn' => '3275062004',
                'parent' => 'Yulianti',
                'classroom' => 'TPA 1',
                'birth_date' => Carbon::now()->subYears(8)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 2',
                'school_grade' => '2 SD',
                'status' => 'nonaktif',
                'approval_status' => 'pending',
                'approved_at' => null,
                'kk_file' => 'kk/kk.jpeg',
                'birth_certificate_file' => 'akta/akta-rejected.jpeg',
            ],

            // TPA 2 aktif semua
            [
                'name' => 'Anbiya Suryadi Haikal',
                'nisn' => '3275062005',
                'parent' => 'Muhammad Fajar Ramadhan',
                'classroom' => 'TPA 2',
                'birth_date' => Carbon::now()->subYears(9)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 1',
                'school_grade' => '3 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(5),
            ],

            [
                'name' => 'Ghefira Kanaya',
                'nisn' => '3275062006',
                'parent' => 'Muhammad Fajar Ramadhan',
                'classroom' => 'TPA 2',
                'birth_date' => '2017-06-13',
                'school_origin' => 'SDN Palumbonsari 3',
                'school_grade' => '3 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(5),
            ],

            [
                'name' => 'M. Fahri Gunawan',
                'nisn' => '3275062007',
                'parent' => 'Muhammad Fajar Ramadhan',
                'classroom' => 'TPA 2',
                'birth_date' => Carbon::now()->subYears(9)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 1',
                'school_grade' => '3 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(4),
            ],

            [
                'name' => 'Azalea Khaliqa Dzahira',
                'nisn' => '3275062008',
                'parent' => 'Siti Nurjanah',
                'classroom' => 'TPA 2',
                'birth_date' => Carbon::now()->subYears(9)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 2',
                'school_grade' => '3 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(3),
            ],

            // DTA 1
            [
                'name' => 'Attaya Alfarezqi',
                'nisn' => '3275062009',
                'parent' => 'Siti Nurjanah',
                'classroom' => 'DTA 1',
                'birth_date' => Carbon::now()->subYears(10)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 2',
                'school_grade' => '4 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(5),
            ],

            [
                'name' => 'Jovita Florida Jacob',
                'nisn' => '3275062010',
                'parent' => 'Siti Nurjanah',
                'classroom' => 'DTA 1',
                'birth_date' => Carbon::now()->subYears(10)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 1',
                'school_grade' => '4 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(4),
            ],

            [
                'name' => 'Ris Arsy Mulyana',
                'nisn' => '3275062011',
                'parent' => 'Dewi Lestari',
                'classroom' => 'DTA 1',
                'birth_date' => Carbon::now()->subYears(10)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 3',
                'school_grade' => '4 SD',
                'status' => 'nonaktif',
                'approval_status' => 'pending',
                'approved_at' => null,
                'kk_file' => 'kk/kk.jpeg',
                'birth_certificate_file' => 'akta/akta.jpeg',
            ],

            [
                'name' => 'Rizki Danu',
                'nisn' => '3275062012',
                'parent' => 'Dewi Lestari',
                'classroom' => 'DTA 1',
                'birth_date' => Carbon::now()->subYears(10)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 2',
                'school_grade' => '4 SD',
                'status' => 'ditolak',
                'approval_status' => 'rejected',
                'approved_at' => null,
                'reject_reason' => 'Bukti transfer tidak sesuai!',
                'kk_file' => 'kk/kk.jpeg',
                'birth_certificate_file' => 'akta/akta.jpeg',
            ],

            // DTA 2 aktif semua
            [
                'name' => 'Arteta Guitarma',
                'nisn' => '3275062013',
                'parent' => 'Sarah Kartini',
                'classroom' => 'DTA 2',
                'birth_date' => Carbon::now()->subYears(11)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 1',
                'school_grade' => '5 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(6),
            ],

            [
                'name' => 'Kelvin Dani Suntana',
                'nisn' => '3275062014',
                'parent' => 'Sarah Kartini',
                'classroom' => 'DTA 2',
                'birth_date' => Carbon::now()->subYears(11)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 2',
                'school_grade' => '5 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(6),
            ],

            [
                'name' => 'Raisya Sakinah',
                'nisn' => '3275062015',
                'parent' => 'Nur Aisyah',
                'classroom' => 'DTA 2',
                'birth_date' => Carbon::now()->subYears(11)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 1',
                'school_grade' => '5 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(4),
            ],

            // DTA 3
            [
                'name' => 'Ayunda Sri Rahayu',
                'nisn' => '3275062016',
                'parent' => 'Nur Aisyah',
                'classroom' => 'DTA 3',
                'birth_date' => Carbon::now()->subYears(12)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 3',
                'school_grade' => '6 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(5),
            ],

            [
                'name' => 'M. Ernest Faiza',
                'nisn' => '3275062017',
                'parent' => 'Rina Marlina',
                'classroom' => 'DTA 3',
                'birth_date' => '2014-06-12',
                'school_origin' => 'SDN Lamaran 2',
                'school_grade' => '6 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(3),
            ],

            [
                'name' => 'Alif Hafizh',
                'nisn' => '3275062018',
                'parent' => 'Rina Marlina',
                'classroom' => 'DTA 3',
                'birth_date' => Carbon::now()->subYears(12)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 1',
                'school_grade' => '6 SD',
                'status' => 'ditolak',
                'approval_status' => 'rejected',
                'approved_at' => null,
                'reject_reason' => 'Bukti transfer & kartu keluarga tidak sesuai!',
                'kk_file' => 'kk/kk-rejected.jpeg',
                'birth_certificate_file' => 'akta/akta.jpeg',
            ],

            // DTA 4
            [
                'name' => 'Tohu Kenzie Pratama',
                'nisn' => '3275062019',
                'parent' => 'Tati Hartati',
                'classroom' => 'DTA 4',
                'birth_date' => Carbon::now()->subYears(13)->format('Y-m-d'),
                'school_origin' => 'SDN Lamaran 1',
                'school_grade' => '6 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(5),
            ],

            [
                'name' => 'M. Rizki Permana',
                'nisn' => '3275062020',
                'parent' => 'Tati Hartati',
                'classroom' => 'DTA 4',
                'birth_date' => Carbon::now()->subYears(13)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 2',
                'school_grade' => '6 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(4),
            ],

            [
                'name' => 'Azalea Khaliqa',
                'nisn' => '3275062021',
                'parent' => 'Sulastri',
                'classroom' => 'DTA 4',
                'birth_date' => Carbon::now()->subYears(13)->format('Y-m-d'),
                'school_origin' => 'SDN Palumbonsari 3',
                'school_grade' => '6 SD',
                'status' => 'aktif',
                'approval_status' => 'approved',
                'approved_at' => now()->subMonths(2),
            ],

        ];

        foreach ($studentsData as $data) {

            $birthDate = Carbon::parse(
                $data['birth_date']
            )->format('dmY');

            $emailName = strtolower(
                str_replace([' ', '.', "'"], '', $data['name'])
            );

            $email = $emailName . $birthDate . '@gmail.com';

            $studentUser = User::where('email', $email)->first();

            $password = $birthDate;

            if (!$studentUser) {

                $studentUser = User::create([
                    'name' => $data['name'],
                    'email' => $email,
                    'phone' => null,
                    'password' => Hash::make($password),
                    'status' => $data['status'] === 'aktif'
                        ? 'aktif'
                        : 'nonaktif',
                    'approval_status' => $data['approval_status'],
                    'address' => 'Karawang',
                    'role' => 'siswa',
                ]);
            }

            $studentUser->update([
                'status' => $data['status'] === 'aktif'
                    ? 'aktif'
                    : 'nonaktif',
                'approval_status' => $data['approval_status'],
                'role' => 'siswa',
            ]);

            $studentUser->assignRole('siswa');

            $student = Student::where(
                'user_id',
                $studentUser->id
            )->first();

            if (!$student) {

                Student::create([
                    'user_id' => $studentUser->id,
                    'parent_id' => $parents[$data['parent']]?->id,
                    'classroom_id' => $classrooms[$data['classroom']]?->id,
                    'academic_year_id' => $academicYear?->id,
                    'name' => $data['name'],
                    'nisn' => $data['nisn'],
                    'birth_date' => $data['birth_date'],
                    'gender' => 'L',
                    'school_origin' => $data['school_origin'],
                    'school_grade' => $data['school_grade'],
                    'kk_file' => $data['kk_file'] ?? 'kk/kk.jpeg',
                    'birth_certificate_file' => $data['birth_certificate_file'] ?? 'akta/akta.jpeg',
                    'status' => $data['status'],
                    'approved_at' => $data['approved_at'],
                    'reject_reason' => $data['reject_reason'] ?? null,
                ]);
            }
        }
    }
}