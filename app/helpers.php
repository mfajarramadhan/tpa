<?php

use App\Models\AcademicYear;

if (!function_exists('activeAcademicYear')) {

    function activeAcademicYear()
    {
        return AcademicYear::where('is_active', true)
            ->first();
    }
}