<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanKriteria extends Model
{
    use HasFactory;

    protected $table = 'permohonan_kriteria';

    protected $fillable = [
        'permohonan_konseling_id',
        'kriteria_id',
        'kriteria_nama',
        'sub_kriteria_nama',
        'skor',
        'bobot',
    ];
    public function permohonan()
    {
        return $this->belongsTo(PermohonanKonseling::class, 'permohonan_konseling_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
