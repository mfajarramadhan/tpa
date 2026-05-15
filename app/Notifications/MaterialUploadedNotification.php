<?php

namespace App\Notifications;

use App\Models\Material;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaterialUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Material $material
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
        return [

            'title' =>

                $this->material->is_task
                    ? 'Tugas Baru'
                    : 'Materi Baru',

            'message' =>

                ($this->material->is_task
                    ? 'Tugas baru'
                    : 'Materi baru')

                . ' pada mata pelajaran '

                . $this->material->subject->name

                . ': '

                . $this->material->title,

            'material_id' => $this->material->id

        ];
    }
}