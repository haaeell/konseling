<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanKonseling;
use App\Models\KategoriKonseling;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $query = PermohonanKonseling::with(['siswa.user', 'kategori'])
            ->where('status', 'selesai'); // hanya yang sudah selesai

        // Filter berdasarkan bulan & tahun
        if ($month) {
            $query->whereMonth('tanggal_pengajuan', $month);
        }

        if ($year) {
            $query->whereYear('tanggal_pengajuan', $year);
        }

        // 🔒 Role-based access
        if (Auth::check()) {
            $user = Auth::user();

            switch ($user->role) {
                case 'siswa':
                    $query->where('siswa_id', $user->siswa->id ?? null);
                    break;

                case 'wali_kelas':
                    $query->whereHas('siswa', function ($q) use ($user) {
                        $q->where('kelas_id', $user->waliKelas->kelas_id ?? null);
                    });
                    break;

                case 'orangtua':
                    $query->whereHas('siswa.orangtua', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                    break;

                case 'guru_bk':
                case 'kepala_sekolah':
                    // akses penuh
                    break;

                default:
            }
        }

        $laporan = $query->get();
        $totalKonseling = $laporan->count();

        $kategoriCounts = KategoriKonseling::withCount(['permohonan' => function ($q) use ($month, $year) {
            if ($month) {
                $q->whereMonth('tanggal_pengajuan', $month);
            }
            if ($year) {
                $q->whereYear('tanggal_pengajuan', $year);
            }
        }])
            ->orderByDesc('permohonan_count')
            ->get();

        return view('laporan.index', compact(
            'laporan',
            'totalKonseling',
            'kategoriCounts',
            'month',
            'year'
        ));
    }
}
