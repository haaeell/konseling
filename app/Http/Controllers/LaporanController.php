<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanKonseling;
use App\Models\TahunAkademik;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaranList = TahunAkademik::orderBy('tahun', 'desc')->get();
        $kelasList = Kelas::with('tahunAkademik')->orderBy('nama')->get();
        $guruList = Guru::where('role_guru', 'bk')->with('user')->get();

        $query = PermohonanKonseling::with([
            'siswa.user',
            'siswa.kelas',
            'guruBk.user'
        ]);

        // Jika user adalah orangtua, hanya tampilkan laporan anak mereka
        if (Auth::user()->isOrangTua()) {
            $orangtua = Auth::user()->orangtua;
            if ($orangtua && $orangtua->siswa_id) {
                $query->where('siswa_id', $orangtua->siswa_id);
            }
        }

        // Filter Status (default selesai jika tidak dipilih)
        $status = $request->status ?? 'selesai';
        if ($status) {
            $query->where('status', $status);
        }

        if ($request->tahun_akademik) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('tahun_akademik_id', $request->tahun_akademik);
            });
        }

        if ($request->kelas) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas);
            });
        }

        // Filter Konselor
        if ($request->konselor) {
            $query->where('nama_konselor', 'like', '%' . $request->konselor . '%');
        }

        // Filter Tanggal (dari)
        if ($request->tanggal_dari) {
            $query->where('tanggal_disetujui', '>=', $request->tanggal_dari);
        }

        // Filter Tanggal (sampai)
        if ($request->tanggal_sampai) {
            $query->where('tanggal_disetujui', '<=', $request->tanggal_sampai);
        }

        $laporan = $query->orderBy('tanggal_disetujui', 'desc')->get();

        return view('laporan.index', [
            'laporan' => $laporan,
            'tahunAjaranList' => $tahunAjaranList,
            'kelasList' => $kelasList,
            'guruList' => $guruList,
            'request' => $request
        ]);
    }

    public function cetakPdf(Request $request)
    {
        $tahunAjaranList = TahunAkademik::orderBy('tahun', 'desc')->get();

        $query = PermohonanKonseling::with([
            'siswa.user',
            'siswa.kelas'
        ]);

        // Jika user adalah orangtua, hanya tampilkan laporan anak mereka
        if (Auth::user()->isOrangTua()) {
            $orangtua = Auth::user()->orangtua;
            if ($orangtua && $orangtua->siswa_id) {
                $query->where('siswa_id', $orangtua->siswa_id);
            }
        }

        // Filter Status (default selesai)
        $status = $request->status ?? 'selesai';
        if ($status) {
            $query->where('status', $status);
        }

        if ($request->tahun_akademik) {
            $query->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('tahun_akademik_id', $request->tahun_akademik);
            });
        }

        if ($request->kelas) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas);
            });
        }

        // Filter Konselor
        if ($request->konselor) {
            $query->where('nama_konselor', 'like', '%' . $request->konselor . '%');
        }

        // Filter Tanggal (dari)
        if ($request->tanggal_dari) {
            $query->where('tanggal_disetujui', '>=', $request->tanggal_dari);
        }

        // Filter Tanggal (sampai)
        if ($request->tanggal_sampai) {
            $query->where('tanggal_disetujui', '<=', $request->tanggal_sampai);
        }

        $laporan = $query->orderBy('tanggal_disetujui', 'desc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', [
            'laporan' => $laporan,
            'request' => $request,
            'tahunAjaranList' => $tahunAjaranList
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan_konseling_' . now()->format('Ymd') . '.pdf');
    }
}
