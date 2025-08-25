<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = ['nama', 'tahun_akademik_id'];
    protected $table = 'kelas';

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function guru()
    {
        return $this->hasMany(Guru::class);
    }
}
