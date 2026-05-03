<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔹 ORANG TUA (REZA)
        $parent = User::where('email', 'reza@gmail.com')->first();

        if (!$parent) {
            $parent = User::create([
                'name' => 'Reza',
                'email' => 'reza@gmail.com',
                'password' => Hash::make('reza12345'),
                'status' => 'aktif',
                'approval_status' => 'approved',
                'address' => 'Karawang'
            ]);
        }

        $parent->assignRole('orang_tua');

        // 🔹 AMBIL KELAS 2
        $classroom = Classroom::where('name', 'like', '%2%')->first();

        // 🔥 DATA ANAK (BISA DITAMBAH TERUS)
        $studentsData = [
            [
                'name' => 'Rama',
                'email' => 'rama02022022@gmail.com',
                'nisn' => '3275061210',
            ],
            [
                'name' => 'Vito',
                'email' => 'vito02022022@gmail.com',
                'nisn' => '3275061211',
            ],
            [
                'name' => 'Yasin',
                'email' => 'yasin02022022@gmail.com',
                'nisn' => '3275061212',
            ],
        ];

        foreach ($studentsData as $data) {

            // 🔹 USER SISWA
            $studentUser = User::where('email', $data['email'])->first();
            $birthDate = Carbon::parse('2022-02-02')->format('dmY');

            if (!$studentUser) {
                $studentUser = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($birthDate),
                    'status' => 'aktif',
                    'approval_status' => 'approved',
                    'address' => 'Karawang',
                    'role' => 'siswa'
                ]);
            }

            $studentUser->assignRole('siswa');

            // 🔹 CEK STUDENT BIAR GA DUPLICATE
            $student = Student::where('user_id', $studentUser->id)->first();

            if (!$student) {
                $student = Student::create([
                    'user_id' => $studentUser->id,
                    'parent_id' => $parent->id,
                    'name' => $data['name'],
                    'nisn' => $data['nisn'],
                    'birth_date' => '2022-02-02',
                    'gender' => 'L',
                    'school_origin' => 'SDN KALIBARU 1',
                    'classroom_id' => $classroom?->id,
                    'kk_file' => 'kk/0TjlvsDxxZexqujklYBIwJgDHepzMc1H5FjFpAld.png',
                    'birth_certificate_file' => 'akta/s7hsfay5ZI8SOHZ6jv79BDp0byPYVpkParrecLUi.png',
                    'status' => 'aktif',
                    'reject_reason' => null,
                ]);
            }

            // 🔹 PAYMENT (CEK BIAR GA DOUBLE)
            $existingPayment = Payment::where('student_id', $student->id)
                ->where('type', 'registration')
                ->first();

            if (!$existingPayment) {
                Payment::create([
                    'student_id' => $student->id,
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

