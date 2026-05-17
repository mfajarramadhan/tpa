<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /*
    =====================================================
    INDEX
    =====================================================
    */
    public function index()
    {
        $classrooms = Classroom::withCount([
                'students' => function ($q) {
                    $q->where('status', 'aktif');
                }
            ])
            ->get();

        return view(
            'promotions.index',
            compact('classrooms')
        );
    }

    /*
    =====================================================
    SHOW
    =====================================================
    */
    public function show(Classroom $classroom)
    {
        $students = Student::where(
                'classroom_id',
                $classroom->id
            )
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NEXT CLASS
        |--------------------------------------------------------------------------
        */
        $nextClass = match ($classroom->name) {

            'TPA 1' => 'TPA 2',
            'TPA 2' => 'DTA 1',

            'DTA 1' => 'DTA 2',
            'DTA 2' => 'DTA 3',
            'DTA 3' => 'DTA 4',

            'DTA 4' => 'Alumni',

            default => null
        };

        return view(
            'promotions.show',
            compact(
                'classroom',
                'students',
                'nextClass'
            )
        );
    }

    /*
    =====================================================
    PROCESS
    =====================================================
    */
    public function process(Request $request, Classroom $classroom)
    {
        $request->validate([
            'students' => 'required|array'
        ]);

        $students = Student::whereIn(
                'id',
                $request->students
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | NEXT CLASS
        |--------------------------------------------------------------------------
        */
        $nextClassName = match ($classroom->name) {

            'TPA 1' => 'TPA 2',
            'TPA 2' => 'DTA 1',

            'DTA 1' => 'DTA 2',
            'DTA 2' => 'DTA 3',
            'DTA 3' => 'DTA 4',

            'DTA 4' => 'Alumni',

            default => null
        };

        /*
        |--------------------------------------------------------------------------
        | ALUMNI
        |--------------------------------------------------------------------------
        */
        if ($nextClassName == 'Alumni') {

            foreach ($students as $student) {

                $student->update([
                    'status' => 'alumni',
                    'classroom_id' => null
                ]);

            }

            return back()->with(
                'success',
                'Siswa berhasil diluluskan!'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NEXT CLASSROOM
        |--------------------------------------------------------------------------
        */
        $nextClassroom = Classroom::where(
            'name',
            $nextClassName
        )->first();

        foreach ($students as $student) {

            $student->update([
                'classroom_id' => $nextClassroom->id
            ]);

        }

        return back()->with(
            'success',
            'Kenaikan kelas berhasil diproses!'
        );
    }
}