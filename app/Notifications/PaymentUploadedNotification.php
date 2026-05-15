<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Payment $payment
    ) {}

    /*
    =====================================================
    CHANNEL
    =====================================================
    */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /*
    =====================================================
    DATABASE
    =====================================================
    */
    public function toDatabase(object $notifiable): array
    {
        if ($this->payment->type == 'registration') {

            return [

                'title' => 'Pendaftaran Baru!',

                'message' =>

                    'Siswa baru atas nama '

                    . $this->payment->student->name .

                    ' telah mendaftar! Menunggu persetujuan.',

                'payment_id' => $this->payment->id

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | IURAN BULANAN
        |--------------------------------------------------------------------------
        */
        return [

            'title' => 'Iuran Baru!',

            'message' =>

                'Pembayaran iuran atas nama '

                . $this->payment->student->name .

                ' - '  .

                $this->payment->student->classroom->name .

                ' menunggu persetujuan!',

            'payment_id' => $this->payment->id

        ];
    }
}