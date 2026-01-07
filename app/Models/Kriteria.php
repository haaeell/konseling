<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $fillable = ['nama', 'bobot'];

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class);
    }
}
