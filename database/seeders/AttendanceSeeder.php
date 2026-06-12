<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $teacher = User::role('guru')->first();

        $classrooms = Classroom::orderBy('id')->get();

        $sickNotes = [
            'Demam',
            'Batuk pilek',
            'Sakit perut',
            'Kurang sehat',
        ];

        $permissionNotes = [
            'Acara keluarga',
            'Piknik keluarga',
            'Nenek meninggal',
        ];

        $alphaNotes = [
            'Tanpa keterangan',
        ];

        foreach ($classrooms as $classroom) {

            $students = Student::where('classroom_id', $classroom->id)
                ->where('status', 'aktif')
                ->orderBy('name')
                ->get();

            if ($students->isEmpty()) {
                continue;
            }

            for ($i = 14; $i >= 1; $i--) {

                $date = Carbon::now()
                    ->subDays($i)
                    ->toDateString();

                // ABSENSI PAGI
                $attendancePagi = Attendance::updateOrCreate(
                    [
                        'classroom_id' => $classroom->id,
                        'academic_year_id' => $academicYear?->id,
                        'date' => $date,
                        'session' => 'pagi',
                    ],
                    [
                        'created_by' => $teacher?->id,
                    ]
                );

                $pagiStatus = [];

                foreach ($students as $student) {

                    $status = $this->randomMorningStatus();

                    $note = match ($status) {
                        'sakit' => $sickNotes[array_rand($sickNotes)],
                        'izin' => $permissionNotes[array_rand($permissionNotes)],
                        'alpha' => $alphaNotes[array_rand($alphaNotes)],
                        default => null,
                    };

                    $pagiStatus[$student->id] = [
                        'status' => $status,
                        'note' => $note,
                    ];

                    AttendanceDetail::updateOrCreate(
                        [
                            'attendance_id' => $attendancePagi->id,
                            'student_id' => $student->id,
                        ],
                        [
                            'status' => $status,
                            'note' => $status === 'hadir' ? null : $note,
                            'updated_by' => $teacher?->id,
                        ]
                    );
                }

                // ABSENSI SORE
                $attendanceSore = Attendance::updateOrCreate(
                    [
                        'classroom_id' => $classroom->id,
                        'academic_year_id' => $academicYear?->id,
                        'date' => $date,
                        'session' => 'sore',
                    ],
                    [
                        'created_by' => $teacher?->id,
                    ]
                );

                foreach ($students as $student) {

                    $morning = $pagiStatus[$student->id];

                    if ($morning['status'] !== 'alpha') {

                        $status = $morning['status'];
                        $note = $morning['note'];

                    } else {

                        $status = $this->randomAfternoonStatus();

                        $note = match ($status) {
                            'sakit' => $sickNotes[array_rand($sickNotes)],
                            'izin' => $permissionNotes[array_rand($permissionNotes)],
                            'alpha' => $alphaNotes[array_rand($alphaNotes)],
                            default => null,
                        };
                    }

                    AttendanceDetail::updateOrCreate(
                        [
                            'attendance_id' => $attendanceSore->id,
                            'student_id' => $student->id,
                        ],
                        [
                            'status' => $status,
                            'note' => $status === 'hadir' ? null : $note,
                            'updated_by' => $teacher?->id,
                        ]
                    );
                }
            }
        }
    }

    private function randomMorningStatus(): string
    {
        $statuses = [
            'hadir', 'hadir', 'hadir', 'hadir', 'hadir',
            'hadir', 'hadir', 'hadir',
            'sakit',
            'izin',
            'alpha',
        ];

        return $statuses[array_rand($statuses)];
    }

    private function randomAfternoonStatus(): string
    {
        $statuses = [
            'hadir', 'hadir', 'hadir',
            'sakit',
            'izin',
            'alpha',
        ];

        return $statuses[array_rand($statuses)];
    }
}