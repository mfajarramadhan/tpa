<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubmissionUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Submission $submission
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
            'title' => 'Submission Baru',

            'message' =>

                $this->submission->student->name .

                ' - '  .

                $this->submission->material->subject->classroom->name .

                ' telah mengumpulkan tugas "' .

                $this->submission->material->title .

                '"',

            'submission_id' => $this->submission->id
        ];
    }
}