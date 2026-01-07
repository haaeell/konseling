<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $table = 'sub_kriterias';
    protected $fillable = ['kriteria_id', 'nama_sub', 'skor'];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
