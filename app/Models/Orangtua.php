<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'hubungan_dengan_siswa',
        'no_hp',
        'alamat',
        'siswa_id'
    ];
    protected $table = 'orangtua';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
