<?php

namespace App\Notifications;

use App\Models\PermohonanKonseling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanKonselingBaru extends Notification implements ShouldQueue
{
    use Queueable;

    public $permohonan;

    public function __construct(PermohonanKonseling $permohonan)
    {
        $this->permohonan = $permohonan;
        $this->delay = now()->addSeconds(2); // Delay 2 detik untuk menghindari rate limiting
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
            'status' => 'menunggu',
            'message' => "Permohonan konseling baru dari {$this->permohonan->siswa->user->name}",
            'action_url' => route('permohonan-konseling.index'),
            'type' => 'permohonan_baru',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Permohonan Konseling Baru - Menunggu Persetujuan')
            ->view('emails.permohonan-baru', [
                'permohonan' => $this->permohonan,
                'notifiable' => $notifiable,
            ]);
    }
}
