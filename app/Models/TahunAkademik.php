<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $fillable = ['tahun'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
