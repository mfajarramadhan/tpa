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
            'name.regex' => 'Format tahun akademik harus 2025/2026'
        ]);

        // VALIDASI TAHUN BERURUTAN CONTOH: 2025/2026
        [$startYear, $endYear] = explode('/', $request->name);

        if ((int)$endYear !== ((int)$startYear + 1)) {

            return back()->withErrors([
                'name' => 'Tahun akademik harus berurutan'
            ])->withInput();
        }

        AcademicYear::create([
            'name' => $request->name
        ]);

        return back()->with(
            'success',
            'Tahun akademik berhasil ditambahkan'
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
            'Tahun akademik aktif berhasil diubah'
        );
    }

    /*
    =====================================================
    DELETE
    =====================================================
    */
    public function destroy(AcademicYear $academicYear)
    {
        // jangan hapus yg aktif
        if ($academicYear->is_active) {

            return back()->with(
                'error',
                'Tahun akademik aktif tidak bisa dihapus'
            );
        }

        $academicYear->delete();

        return back()->with(
            'success',
            'Tahun akademik berhasil dihapus'
        );
    }
}