<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'role_guru',
        'kelas_id'
    ];
    protected $table = 'guru';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
