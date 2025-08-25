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

    public function hitungSkor()
    {
        $TU = $this->tingkat_urgensi;
        $DM = $this->dampak_masalah;
        $KM = $this->kategori_masalah;
        $RK = $this->riwayat_konseling;

        $this->skor_prioritas = ($TU * 0.4) + ($DM * 0.3) + ($KM * 0.2) + ($RK * 0.1);
        $this->save();
    }
}
