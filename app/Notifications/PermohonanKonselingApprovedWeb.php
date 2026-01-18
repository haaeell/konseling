<?php

namespace App\Notifications;

use App\Models\PermohonanKonseling;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanKonselingApprovedWeb extends Notification
{
    use Queueable;

    public $permohonan;

    public function __construct(PermohonanKonseling $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function via($notifiable)
    {
        return ['database'];
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
}
