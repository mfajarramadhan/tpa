<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use App\Notifications\MonthlyBillCreatedNotification;

class MonthlyBillService
{
    public function generate()
    {
        // ambil fee terbaru
        $fee = Fee::first();

        // safety
        if (!$fee) {
            return;
        }

        $students = Student::where('status', 'aktif')->get();

        foreach ($students as $student) {

            // tanggal daftar
            $createdAt = Carbon::parse($student->created_at);

            // bulan pertama iuran
            $firstBilling = $createdAt
                ->copy()
                ->addMonth()
                ->startOfMonth();

            // bulan sekarang
            $now = Carbon::now()->startOfMonth();

            // looping
            $current = $firstBilling->copy();

            while ($current <= $now) {

                $month = $current->format('Y-m');

                // cek duplicate
                $exists = Payment::where('student_id', $student->id)
                    ->where('type', 'monthly')
                    ->where('month', $month)
                    ->exists();

                if (!$exists) {

                    $payment = Payment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => activeAcademicYear()->id,
                        'type' => 'monthly',
                        'month' => $month,
                        'original_amount' => $fee->monthly_fee,
                        'amount' => $fee->monthly_fee,
                        'status' => 'pending'
                    ]);

                    // kirim notifikasi ke orang tua
                    $parent = $student->parent;

                    if ($parent) {

                        $parent->notify(
                            new MonthlyBillCreatedNotification($payment)
                        );
                    }
                }

                $current->addMonth();
            }
        }
    }
}