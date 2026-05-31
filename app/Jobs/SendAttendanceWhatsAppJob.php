<?php

namespace App\Jobs;

use App\Models\AttendanceDetail;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAttendanceWhatsAppJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public AttendanceDetail $detail
    ) {
    }

    public function uniqueId(): string
    {
        return 'attendance-wa-' . $this->detail->id . '-' . $this->detail->updated_at?->timestamp;
    }

    public function handle(FonnteService $fonnte): void
    {
        $detail = $this->detail->load([
            'student.parent',
            'attendance.classroom',
        ]);

        $student = $detail->student;
        $attendance = $detail->attendance;

        if (! $student || ! $attendance) {
            return;
        }

        if ($student->status !== 'aktif') {
            return;
        }

        $parent = $student->parent;

        if (! $parent || ! $parent->phone) {
            return;
        }

        $phone = formatPhone($parent->phone);

        if (! $phone) {
            return;
        }

        $statusText = match ($detail->status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => ucfirst($detail->status),
        };

        $message =
            "Assalamu'alaikum Bapak/Ibu.\n\n"
            . "Berikut informasi absensi peserta didik TPA/DTA Al-Barokah tanggal "
            . Carbon::parse($attendance->date)->translatedFormat('d F Y')
            . ":\n\n"
            . "- Nama: {$student->name}\n"
            . "- Kelas: {$attendance->classroom->name}\n"
            . "- Sesi: " . ucfirst($attendance->session) . "\n"
            . "- Status: *{$statusText}*\n";

        if ($detail->note) {
            $message .= "- Keterangan: {$detail->note}\n";
        }

        $message .= "\nTerima kasih.";

        $fonnte->sendMessage($phone, $message);
    }
}