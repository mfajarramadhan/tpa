<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
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

                'title' => 'Pendaftaran Ditolak!',

                'message' =>

                    'Pendaftaran atas nama '

                    . $this->payment->student->name .

                    ' ditolak! '

                    . $this->payment->reject_reason,

                'payment_id' => $this->payment->id

            ];
        }

        // IURAN
        return [

            'title' => 'Bayar Iuran Ditolak!',

            'message' =>

                'Pembayaran iuran atas nama '

                . $this->payment->student->name .

                ' ditolak! '

                . $this->payment->reject_reason,

            'payment_id' => $this->payment->id

        ];
    }
}