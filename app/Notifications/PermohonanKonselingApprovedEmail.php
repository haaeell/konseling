<?php

namespace App\Notifications;

use App\Models\PermohonanKonseling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanKonselingApprovedEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $permohonan;

    public function __construct(PermohonanKonseling $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Permohonan Konseling Disetujui')
            ->view('emails.permohonan-approved', [
                'permohonan' => $this->permohonan,
                'notifiable' => $notifiable,
            ]);
    }
}
