<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = ['password'];

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    public function orangtua()
    {
        return $this->hasOne(Orangtua::class);
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isOrangTua(): bool
    {
        return $this->role === 'orangtua';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isBK(): bool
    {
        return $this->isGuru() && $this->guru && $this->guru->role_guru === 'bk';
    }

    public function isWaliKelas(): bool
    {
        return $this->isGuru() && $this->guru && !is_null($this->guru->kelas_id);
    }

    public function isKepalaSekolah(): bool
    {
        return $this->isGuru() && $this->guru && $this->guru->role_guru === 'kepsek';
    }
}
