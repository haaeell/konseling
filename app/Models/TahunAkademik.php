<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $fillable = ['tahun'];
    protected $table = 'tahun_akademik';

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
