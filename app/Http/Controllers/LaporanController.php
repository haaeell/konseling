<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanKonseling;
use App\Models\KategoriKonseling;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use PDF; // <--- Tambahkan ini

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $query = PermohonanKonseling::with(['siswa.user', 'kategori'])
            ->where('status', 'selesai');

        if ($month) $query->whereMonth('tanggal_pengajuan', $month);
        if ($year) $query->whereYear('tanggal_pengajuan', $year);

        // 🔒 Role filter
        if (Auth::check()) {
            $user = Auth::user();

            switch ($user->role) {
                case 'siswa':
                    $query->where('siswa_id', $user->siswa->id);
                    break;

                case 'guru':
                    if ($user->guru && $user->guru->role_guru === 'walikelas') {
                        $query->whereHas('siswa', function ($q) use ($user) {
                            $q->where('kelas_id', $user->guru->kelas_id);
                        });

                        $siswaWali = Siswa::where('kelas_id', $user->guru->kelas_id)->get();
                    }
                    break;

                default:
                    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        $laporan = $query->get();
        $totalKonseling = $laporan->count();

        $kategoriCounts = KategoriKonseling::withCount(['permohonan' => function ($q) use ($month, $year) {
            if ($month) $q->whereMonth('tanggal_pengajuan', $month);
            if ($year) $q->whereYear('tanggal_pengajuan', $year);
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

    // === 🧾 CETAK PDF ===
    public function cetakPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $query = PermohonanKonseling::with(['siswa.user', 'kategori'])
            ->where('status', 'selesai');

        if ($month) $query->whereMonth('tanggal_pengajuan', $month);
        if ($year) $query->whereYear('tanggal_pengajuan', $year);

        $laporan = $query->get();
        $totalKonseling = $laporan->count();

        $pdf = FacadePdf::loadView('laporan.pdf', [
            'laporan' => $laporan,
            'month' => $month,
            'year' => $year,
            'totalKonseling' => $totalKonseling
        ])->setPaper('a4', 'portrait');

        $namaFile = 'laporan_konseling_' . ($month ?? 'semua') . '_' . ($year ?? now()->year) . '.pdf';

        return $pdf->download($namaFile);
    }
}
