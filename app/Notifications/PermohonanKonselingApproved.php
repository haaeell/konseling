<?php

namespace App\Notifications;

use App\Models\PermohonanKonseling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanKonselingApproved extends Notification implements ShouldQueue
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
            'status' => 'disetujui',
            'message' => "Permohonan konseling Anda telah disetujui",
            'action_url' => route('permohonan-konseling.index'),
            'type' => 'permohonan_approved',
        ];
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
