<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MonthlyBillCreatedNotification extends Notification
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $student = $this->payment->student;

        return [

            'title' => 'Iuran Baru!',

            'message' =>
                'Iuran bulanan baru atas nama '
                . $student->name .
                ' - '
                . ($student->classroom->name ?? '-') .
                ' pada bulan '
                . \Carbon\Carbon::createFromFormat(
                    'Y-m',
                    $this->payment->month
                )->translatedFormat('F Y'),

            'url' => route(
                'payments.index',
                ['student_id' => $student->id]
            ),

            'icon' => 'solar:wallet-money-bold-duotone'
        ];
    }
}