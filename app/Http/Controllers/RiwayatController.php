<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonseling;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayatKonseling = PermohonanKonseling::with(['siswa.user', 'kategori'])
            ->where('status', 'selesai')
            ->orderBy('skor_prioritas', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        $kategoriKonseling = KategoriKonseling::all();
        return view('riwayat-konseling.index', compact('riwayatKonseling', 'kategoriKonseling'));
    }
}
