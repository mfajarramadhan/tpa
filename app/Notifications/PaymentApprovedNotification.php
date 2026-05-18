<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Payment $payment
    ) {}

    /*
    |--------------------------------------------------------------------------
    | CHANNEL
    |--------------------------------------------------------------------------
    */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /*
    |--------------------------------------------------------------------------
    | DATABASE
    |--------------------------------------------------------------------------
    */
    public function toDatabase(object $notifiable): array
    {

        // REGISTRASI
        if ($this->payment->type == 'registration') {

            return [

                'title' => 'Pendaftaran Disetujui!',

                'message' =>

                    'Pendaftaran atas nama '

                    . $this->payment->student->name .

                    ' berhasil disetujui!',

                'payment_id' => $this->payment->id

            ];
        }

        // IURAN
        return [

            'title' => 'Iuran Disetujui!',

            'message' =>

                'Pembayaran iuran atas nama '

                . $this->payment->student->name .

                ' berhasil disetujui!',

            'payment_id' => $this->payment->id

        ];
    }
}