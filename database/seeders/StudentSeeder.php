<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // orang tua (reza)
        $parent = User::firstOrCreate(
            ['email' => 'sarah@gmail.com'],
            [
                'name' => 'Sarah Kartini',
                'phone' => '0895629370698',
                'password' => Hash::make('sarah12345'),
                'status' => 'aktif',
                'approval_status' => 'approved',
                'address' => 'Karawang',
                'role' => 'orang_tua',
            ]
        );

        // orang tua (riska)
        $parent2 = User::firstOrCreate(
            ['email' => 'rini@gmail.com'],
            [
                'name' => 'Rini Sotyaningsih',
                'phone' => '082258667392',
                'password' => Hash::make('rini12345'),
                'status' => 'aktif',
                'approval_status' => 'approved',
                'address' => 'Karawang',
                'role' => 'orang_tua',
            ]
        );

        $parent->assignRole('orang_tua');
        $parent2->assignRole('orang_tua');

        // ambil kelas 2
        $classroom = Classroom::where('name', 'like', '%2%')->first();

        // data anak
        $studentsData = [
            [
                'name' => 'Faris',
                'email' => 'faris01012018@gmail.com',
                'nisn' => '3275061213',
                'parent_id' => $parent->id,
                'status' => 'nonaktif',
            ],
            [
                'name' => 'Rama',
                'email' => 'rama01012018@gmail.com',
                'nisn' => '3275061210',
                'parent_id' => $parent->id,
                'status' => 'aktif',
            ],
            [
                'name' => 'Vito',
                'email' => 'vito01012018@gmail.com',
                'nisn' => '3275061211',
                'parent_id' => $parent2->id,
                'status' => 'nonaktif',
            ],
            [
                'name' => 'Yasin',
                'email' => 'yasin01012018@gmail.com',
                'nisn' => '3275061212',
                'parent_id' => $parent2->id,
                'status' => 'aktif',
            ],
        ];

        foreach ($studentsData as $data) {

            // user siswa
            $studentUser = User::where('email', $data['email'])->first();
            $birthDate = Carbon::parse('2018-01-01')->format('dmY');

            if (!$studentUser) {

                $studentUser = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($birthDate),
                    'status' => $data['status'],
                    'approval_status' => $data['status'] === 'aktif' ? 'approved' : 'pending',
                    'address' => 'Karawang',
                    'role' => 'siswa'
                ]);
            }

            // sinkronkan status user siswa dengan status student
            $studentUser->update([
                'status' => $data['status'],
                'approval_status' => $data['status'] === 'aktif' ? 'approved' : 'pending',
            ]);

            $studentUser->assignRole('siswa');

            // cek student biar ga duplicate
            $student = Student::where('user_id', $studentUser->id)->first();

            if (!$student) {

                $student = Student::create([
                    'user_id' => $studentUser->id,
                    'parent_id' => $data['parent_id'],
                    'classroom_id' => $classroom?->id,
                    'academic_year_id' => AcademicYear::where('is_active', true)->first()->id,
                    'name' => $data['name'],
                    'nisn' => $data['nisn'],
                    'birth_date' => '2018-01-01',
                    'gender' => 'L',
                    'school_origin' => 'SDN KALIBARU 1',
                    'school_grade' => '2 SD',
                    'kk_file' => 'kk/0TjlvsDxxZexqujklYBIwJgDHepzMc1H5FjFpAld.png',
                    'birth_certificate_file' => 'akta/s7hsfay5ZI8SOHZ6jv79BDp0byPYVpkParrecLUi.png',
                    'status' => $data['status'],
                    'approved_at' => $data['status'] === 'aktif' ? now()->subMonths(2) : null,
                    'reject_reason' => null,
                ]);
            }

            // payment (cek biar ga double)
            $existingPayment = Payment::where('student_id', $student->id)
                ->where('type', 'registration')
                ->first();

            if (!$existingPayment) {

                Payment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => AcademicYear::where('is_active', true)->first()->id,
                    'type' => 'registration',
                    'month' => null,
                    'original_amount' => 50000,
                    'amount' => 50000,
                    'proof_file' => 'payments/yrhYLwGg8wVhQuhpCCp7cjAkcTQw1BZYtUBqdRVo.png',
                    'status' => 'paid',
                    'paid_at' => now(),
                    'approved_by' => 1
                ]);
            }
        }
    }
}