<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanKonseling extends Model
{
    protected $table = 'permohonan_konseling';

    protected $fillable = [
        'siswa_id',
        'tanggal_pengajuan',
        'deskripsi_permasalahan',
        'status',
        'rangkuman',
        'alasan_penolakan',
        'tanggal_disetujui',
        'tempat',
        'nama_konselor',

        // Kriteria Penilaian
        'tingkat_urgensi_label',
        'tingkat_urgensi_skor',

        'dampak_masalah_label',
        'dampak_masalah_skor',

        'kategori_masalah_label',
        'kategori_masalah_skor',

        'riwayat_konseling_label',
        'riwayat_konseling_skor',

        'skor_prioritas',
        'report_type',
        'bukti_masalah',
    ];

    // Relasi
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guruBk()
    {
        return $this->belongsTo(Guru::class, 'approved_by');
    }

    public function permohonanKriteria()
    {
        return $this->hasMany(PermohonanKriteria::class, 'permohonan_konseling_id');
    }
}
