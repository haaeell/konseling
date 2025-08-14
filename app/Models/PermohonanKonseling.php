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
        'tanggal_disetujui',
        'tempat'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKonseling::class);
    }
}
