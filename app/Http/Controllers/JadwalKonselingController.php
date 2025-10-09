<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonseling;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKonselingController extends Controller
{
   public function index()
{
    // Mulai query dasar
    $query = PermohonanKonseling::with(['siswa.user', 'kategori'])
        ->where('status', 'disetujui')
        ->orderBy('skor_prioritas', 'desc')
        ->orderBy('created_at', 'asc');

    // Batasi data sesuai peran pengguna
    if (Auth::check()) {
        $user = Auth::user();

        switch ($user->role) {
            case 'siswa':
                // Siswa hanya melihat jadwal miliknya
                $query->where('siswa_id', $user->siswa->id);
                break;

           case 'guru':
            if ($user->guru && $user->guru->role_guru === 'walikelas') {
                // Guru wali kelas hanya bisa lihat siswa di kelasnya
                $query->whereHas('siswa', function ($q) use ($user) {
                    $q->where('kelas_id', $user->guru->kelas_id);
                });

                $siswaWali = \App\Models\Siswa::where('kelas_id', $user->guru->kelas_id)->get();
            }
            // Guru BK bisa lihat semua
            break;

            case 'orang_tua':
                // Orang tua hanya melihat jadwal anaknya
                $query->whereHas('siswa', function ($q) use ($user) {
                    $q->where('orang_tua_id', $user->orangTua->id ?? null);
                });
                break;

            // Guru BK atau Kepala Sekolah bisa melihat semua
            default:
                break;
        }
    }

    // Eksekusi query
    $jadwalKonseling = $query->get();

    // Ambil semua kategori konseling
    $kategoriKonseling = KategoriKonseling::all();

    // Kirim data ke view
    return view('jadwal-konseling.index', compact('jadwalKonseling', 'kategoriKonseling'));
}

}
