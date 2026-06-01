<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Notifications\MonthlyBillCreatedNotification;
use Carbon\Carbon;

class MonthlyBillService
{
    public function generate(): void
    {
        // ambil fee terbaru
        $fee = Fee::first();

        // safety jika fee belum ada
        if (!$fee) {
            return;
        }

        // hanya siswa aktif yang sudah di-approve superadmin
        $students = Student::where('status', 'aktif')
            ->whereNotNull('approved_at')
            ->with('parent')
            ->get();

        foreach ($students as $student) {

            // tanggal siswa resmi diterima
            $approvedAt = Carbon::parse($student->approved_at);

            // iuran pertama dimulai 1 bulan setelah approve
            $firstBilling = $approvedAt
                ->copy()
                ->addMonthNoOverflow()
                ->startOfMonth();

            // bulan berjalan
            $now = Carbon::now()->startOfMonth();

            // mulai generate dari bulan pertama iuran
            $current = $firstBilling->copy();

            while ($current <= $now) {

                // format bulan: YYYY-MM
                $month = $current->format('Y-m');

                // buat tagihan jika belum ada
                $payment = Payment::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'type' => 'monthly',
                        'month' => $month,
                    ],
                    [
                        'academic_year_id' => activeAcademicYear()->id,
                        'original_amount' => $fee->monthly_fee,
                        'adjustment' => 0,
                        'amount' => $fee->monthly_fee,
                        'status' => 'pending',
                    ]
                );

                // kirim notifikasi hanya jika tagihan baru dibuat
                if ($payment->wasRecentlyCreated) {

                    $parent = $student->parent;

                    if ($parent) {
                        $parent->notify(
                            new MonthlyBillCreatedNotification($payment)
                        );
                    }
                }

                // lanjut ke bulan berikutnya
                $current->addMonthNoOverflow();
            }
        }
    }
}