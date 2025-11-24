<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanKonseling;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        // Query dasar
        $query = PermohonanKonseling::where('status', 'selesai')
            ->when($month, fn($q) => $q->whereMonth('tanggal_pengajuan', $month))
            ->when($year,  fn($q) => $q->whereYear('tanggal_pengajuan', $year));

        // ⛔ FILTER ROLE
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'siswa') {
                $query->where('siswa_id', $user->siswa->id);
            }

            if ($user->role === 'guru' && $user->guru->role_guru === 'walikelas') {
                $query->whereHas('siswa', function ($q) use ($user) {
                    $q->where('kelas_id', $user->guru->kelas_id);
                });
            }
        }

        $laporan = $query->get();
        $total = $laporan->count();
        $totalPengajuanKonseling = PermohonanKonseling::where('status', 'menunggu')->count();

        // 📊 REKAP (group by label → hitung)
        $rekap = [
            'kategori' => $laporan->groupBy('kategori_masalah_label')->map->count(),
            'urgensi'  => $laporan->groupBy('tingkat_urgensi_label')->map->count(),
            'dampak'   => $laporan->groupBy('dampak_masalah_label')->map->count(),
            'riwayat'  => $laporan->groupBy('riwayat_konseling_label')->map->count(),
        ];

        return view('laporan.index', compact(
            'laporan',
            'total',
            'rekap',
            'month',
            'year',
            'totalPengajuanKonseling'
        ));
    }

    public function cetakPdf(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        $query = PermohonanKonseling::with(['siswa.user'])
            ->where('status', 'selesai');

        if ($month) $query->whereMonth('tanggal_pengajuan', $month);
        if ($year)  $query->whereYear('tanggal_pengajuan', $year);

        $laporan = $query->get();
        $total   = $laporan->count();

        $pdf = Pdf::loadView('laporan.pdf', compact('laporan', 'total', 'month', 'year'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_konseling.pdf');
    }
}
