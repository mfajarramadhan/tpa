<?php

use App\Models\AcademicYear;

if (!function_exists('activeAcademicYear')) {

    function activeAcademicYear()
    {
        return AcademicYear::where('is_active', true)
            ->first();
    }
}

if (! function_exists('formatPhone')) {

    function formatPhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        // hapus selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 08xxxx -> 628xxxx
        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        // 62xxxx -> langsung pakai
        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        return $phone;
    }
}