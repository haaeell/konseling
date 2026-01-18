<?php

namespace App\Notifications;

use App\Models\PermohonanKonseling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanKonselingRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $permohonan;

    public function __construct(PermohonanKonseling $permohonan)
    {
        $this->permohonan = $permohonan;
        $this->delay = now()->addSeconds(2);
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'permohonan_id' => $this->permohonan->id,
            'siswa_name' => $this->permohonan->siswa->user->name,
            'status' => 'ditolak',
            'message' => "Permohonan konseling Anda ditolak",
            'action_url' => route('permohonan-konseling.index'),
            'type' => 'permohonan_rejected',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Permohonan Konseling Ditolak')
            ->view('emails.permohonan-rejected', [
                'permohonan' => $this->permohonan,
                'notifiable' => $notifiable,
            ]);
    }
}
