<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $superadmin = User::role('superadmin')->first();

        $proofFilePaid = 'payments/tf.jpeg';
        $proofFilePending = 'payments/tf.jpeg';
        $proofFileRejected = 'payments/tf-rejected.jpeg';

        $paymentScenarios = [

            // TPA 1
            'Aditiya Alfarizqy' => 'lunas',
            'Dimas Aditya Haidar' => 'menunggak',
            'Miftahul Jannah' => 'tanpa_tagihan',
            'Arjuna Maulana' => 'pending_registration',

            // TPA 2 aktif semua, banyak lunas
            'Anbiya Suryadi Haikal' => 'lunas',
            'Ghesira Kanaya' => 'lunas',
            'M. Fahri Gunawan' => 'menunggu_konfirmasi',
            'Azalea Khaliqa Dzahira' => 'lunas',

            // DTA 1
            'Attaya Alfarezqi' => 'lunas',
            'Jovita Florida Jacob' => 'lunas',
            'Ris Arsy Mulyana' => 'pending_registration',
            'Rizki Danu' => 'rejected_registration',

            // DTA 2 aktif semua
            'Arteta Guitarma' => 'lunas',
            'Kelvin Dani Suntana' => 'lunas',
            'Raisya Sakinah' => 'lunas',

            // DTA 3
            'Ayunda Sri Rahayu' => 'lunas',
            'M. Ernest Faiza' => 'menunggak',
            'Alif Hafizh' => 'rejected_registration',

            // DTA 4
            'Tohu Kenzie Pratama' => 'lunas',
            'M. Rizki Permana' => 'menunggu_konfirmasi',
            'Azalea Khaliqa' => 'lunas',

        ];

        foreach ($paymentScenarios as $studentName => $scenario) {

            $student = Student::where(
                'name',
                $studentName
            )->first();

            if (!$student) {
                continue;
            }

            // registrasi 500 ribu
            $registrationStatus = match ($scenario) {
                'pending_registration' => 'pending',
                'rejected_registration' => 'rejected',
                default => 'paid',
            };

            Payment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'type' => 'registration',
                    'month' => null,
                ],
                [
                    'academic_year_id' => $academicYear?->id,
                    'original_amount' => 500000,
                    'amount' => 500000,

                    'proof_file' => match ($registrationStatus) {
                        'paid' => $proofFilePaid,
                        'pending' => $proofFilePending,
                        'rejected' => $proofFileRejected,
                    },

                    'status' => $registrationStatus,

                    'paid_at' => now()->subDays(3),

                    'approved_by' =>
                        $registrationStatus === 'paid'
                            ? $superadmin?->id
                            : null,

                    'approved_at' =>
                        $registrationStatus === 'paid'
                            ? now()->subMonths(2)
                            : null,

                    'reject_reason' =>
                        $registrationStatus === 'rejected'
                            ? 'Berkas pembayaran pendaftaran tidak sesuai'
                            : null,
                ]
            );

            // siswa pending / ditolak tidak perlu dibuatkan iuran bulanan
            if (
                $scenario === 'pending_registration'
                || $scenario === 'rejected_registration'
            ) {
                continue;
            }

            // tanpa tagihan = baru approved admin, belum ada iuran bulanan
            if ($scenario === 'tanpa_tagihan') {
                continue;
            }

            $startMonth = Carbon::parse(
                $student->approved_at ?? now()
            )->startOfMonth();

            $endMonth = now()->startOfMonth();

            while ($startMonth->lte($endMonth)) {

                $month = $startMonth->format('Y-m');

                $monthlyStatus = 'paid';
                $monthlyProof = $proofFilePaid;
                $paidAt = $startMonth->copy()->addDays(5);
                $approvedBy = $superadmin?->id;
                $approvedAt = $startMonth->copy()->addDays(6);
                $rejectReason = null;

                if ($scenario === 'menunggak') {

                    if ($month === $endMonth->format('Y-m')) {

                        $monthlyStatus = 'pending';
                        $monthlyProof = null;
                        $paidAt = null;
                        $approvedBy = null;
                        $approvedAt = null;
                    }

                } elseif ($scenario === 'menunggu_konfirmasi') {

                    if ($month === $endMonth->format('Y-m')) {

                        $monthlyStatus = 'pending';
                        $monthlyProof = $proofFilePending;
                        $paidAt = now()->subDays(1);
                        $approvedBy = null;
                        $approvedAt = null;
                    }
                }

                Payment::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'type' => 'monthly',
                        'month' => $month,
                    ],
                    [
                        'academic_year_id' => $academicYear?->id,
                        'original_amount' => 100000,
                        'amount' => 100000,
                        'proof_file' => $monthlyProof,
                        'status' => $monthlyStatus,
                        'paid_at' => $paidAt,
                        'approved_by' => $approvedBy,
                        'approved_at' => $approvedAt,
                        'reject_reason' => $rejectReason,
                    ]
                );

                $startMonth->addMonth();
            }
        }
    }
}