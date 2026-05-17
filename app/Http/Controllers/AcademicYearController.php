<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /*
    =====================================================
    LIST
    =====================================================
    */
    public function index()
    {
        $years = AcademicYear::latest()
            ->get();

        return view(
            'academic-years.index',
            compact('years')
        );
    }

    /*
    =====================================================
    STORE
    =====================================================
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'unique:academic_years,name',
                'regex:/^\d{4}\/\d{4}$/'
            ]
        ], [
            'name.regex' => 'Format tidak sesuai! (Contoh: 2026/2027)'
        ]);

        // VALIDASI TAHUN BERURUTAN CONTOH: 2025/2026
        [$startYear, $endYear] = explode('/', $request->name);

        if ((int)$endYear !== ((int)$startYear + 1)) {

            return back()->withErrors([
                'name' => 'Tahun akademik harus berurutan! (Contoh: 2026/2027)'
            ])->withInput();
        }

        AcademicYear::create([
            'name' => $request->name
        ]);

        return back()->with(
            'success',
            'Tahun akademik berhasil ditambahkan!'
        );
    }


    /*
    =====================================================
    EDIT
    =====================================================
    */
    public function edit(AcademicYear $academicYear)
    {
        return view(
            'academic-years.edit',
            compact('academicYear')
        );
    }


    /*
    =====================================================
    UPDATE
    =====================================================
    */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'name' => [
                'required',
                'unique:academic_years,name,' . $academicYear->id,
                'regex:/^\d{4}\/\d{4}$/'
            ]
        ], [
            'name.regex' => 'Format tidak sesuai! (Contoh: 2026/2027)'
        ]);

        // Validasi tahun berurutan
        [$startYear, $endYear] = explode('/', $request->name);

        if ((int)$endYear !== ((int)$startYear + 1)) {

            return back()->withErrors([
                'name' => 'Tahun akademik harus berurutan! (Contoh: 2026/2027)'
            ])->withInput();
        }

        $academicYear->update([
            'name' => $request->name
        ]);

        return redirect()->route('academic-years.index')
            ->with(
                'success',
                'Tahun akademik berhasil diperbarui!'
            );
    }

    /*
    =====================================================
    SET ACTIVE
    =====================================================
    */
    public function setActive(AcademicYear $academicYear)
    {
        // nonaktifkan semua
        AcademicYear::query()->update([
            'is_active' => false
        ]);

        // aktifkan yg dipilih
        $academicYear->update([
            'is_active' => true
        ]);

        return back()->with(
            'success',
            'Tahun akademik aktif berhasil diperbarui!'
        );
    }
}