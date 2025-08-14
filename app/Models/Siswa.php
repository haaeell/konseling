<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'kelas_id',
        'jenis_kelamin',
        'no_telp_orangtua',
        'nama_orangtua',
        'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orangtua()
    {
        return $this->hasMany(Orangtua::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanKonseling::class);
    }
}
