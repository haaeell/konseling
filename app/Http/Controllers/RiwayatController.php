<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonseling;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
   public function index()
{
    $query = PermohonanKonseling::with(['siswa.user', 'kategori'])
        ->where('status', 'selesai')
        ->orderBy('skor_prioritas', 'desc')
        ->orderBy('created_at', 'asc');

    if (Auth::check()) {
        $user = Auth::user();

        switch ($user->role) {
            case 'siswa':
                $query->where('siswa_id', $user->siswa->id);
                break;

            case 'guru':
                    // cek apakah guru adalah wali kelas atau guru BK
                    if ($user->guru && $user->guru->role_guru === 'walikelas') {
                        // wali kelas hanya lihat siswa di kelasnya
                        $query->whereHas('siswa', function ($q) use ($user) {
                            $q->where('kelas_id', $user->guru->kelas_id ?? null);
                        });
                    } elseif ($user->guru && $user->guru->role_guru === 'bk') {
                        // guru BK bisa lihat semua data
                    }
                    break;

            case 'orangtua':
                $query->whereHas('siswa.orangtua', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
                break;

            default:
                break;
        }
    }

    $riwayatKonseling = $query->get();
    $kategoriKonseling = KategoriKonseling::all();

    return view('riwayat-konseling.index', compact('riwayatKonseling', 'kategoriKonseling'));
}

}
