<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanKonseling extends Model
{
    protected $fillable = [
        'siswa_id',
        'kategori_id',
        'tanggal_pengajuan',
        'deskripsi_permasalahan',
        'status',
        'rangkuman',
        'alasan_penolakan',
        'tanggal_disetujui',
        'tempat',
        'skor_prioritas',
    ];

    protected $table = 'permohonan_konseling';

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKonseling::class, 'kategori_id');
    }
}
