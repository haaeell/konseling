<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PermohonanKonselingNotification extends Notification
{
    use Queueable;

    protected $permohonan;
    protected $message;

    public function __construct($permohonan, $message)
    {
        $this->permohonan = $permohonan;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'message' => $this->message,
            'permohonan_id' => $this->permohonan->id,
            'status' => $this->permohonan->status,
            'siswa_name' => $this->permohonan->siswa->user->name,
        ]);
    }
}
