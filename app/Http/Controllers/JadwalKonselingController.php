<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonseling;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;

class JadwalKonselingController extends Controller
{
    public function index()
    {
        $jadwalKonseling = PermohonanKonseling::with(['siswa.user', 'kategori'])
            ->where('status', 'disetujui')
            ->orderBy('skor_prioritas', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        $kategoriKonseling = KategoriKonseling::all();
        return view('jadwal-konseling.index', compact('jadwalKonseling', 'kategoriKonseling'));
    }
}
