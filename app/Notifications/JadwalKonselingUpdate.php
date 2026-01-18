<?php

namespace App\Notifications;

use App\Models\PermohonanKonseling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalKonselingUpdate extends Notification implements ShouldQueue
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
            'message' => "Jadwal konseling Anda telah diupdate",
            'jadwal' => $this->permohonan->tanggal_disetujui?->format('d-m-Y H:i'),
            'tempat' => $this->permohonan->tempat,
            'action_url' => route('permohonan-konseling.index'),
            'type' => 'jadwal_update',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Update Jadwal Konseling')
            ->view('emails.jadwal-update', [
                'permohonan' => $this->permohonan,
                'notifiable' => $notifiable,
            ]);
    }
}
