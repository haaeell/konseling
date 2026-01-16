<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\PermohonanKonseling;
use App\Models\Kriteria;
use App\Models\PermohonanKriteria;
use App\Models\Siswa;
use App\Models\SubKriteria;
use App\Notifications\PermohonanKonselingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use Carbon\Carbon;

class PermohonanKonselingController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access');
        }

        $user = Auth::user();

        $query = PermohonanKonseling::with(['siswa.user'])
            ->where('status', 'menunggu')
            ->orderBy('skor_prioritas', 'desc')
            ->orderBy('created_at', 'asc');

        $siswaWali = collect();

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

        $permohonanKonseling = $query->get();
        $kriteria = Kriteria::all();



        $startDate = Carbon::now()->subMonth()->startOfDay();

        $jumlahRiwayat = PermohonanKonseling::where(
            'siswa_id',
            auth()->user()->siswa->id ?? null,
        )
            ->where('status', 'selesai')
            ->where('created_at', '>=', $startDate)
            ->count();

        return view('permohonan-konseling.index', compact(
            'permohonanKonseling',
            'siswaWali',
            'kriteria',
            'jumlahRiwayat',
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'deskripsi_permasalahan' => 'required|string',
            'siswa_id' => 'required_if:role,guru',
            'bukti_masalah' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,mov,avi,webm|max:10240',
            'kriteria' => 'required|array',
            'sub_kriteria' => 'required|array',
        ]);

        $user = Auth::user();
        $siswaId = $user->siswa->id ?? $request->siswa_id;

        $path = $request->hasFile('bukti_masalah')
            ? $request->file('bukti_masalah')->store('bukti-masalah', 'public')
            : null;
        $permohonan = PermohonanKonseling::create([
            'siswa_id' => $siswaId,
            'tanggal_pengajuan' => now(),
            'deskripsi_permasalahan' => $request->deskripsi_permasalahan,
            'bukti_masalah' => $path,
            'status' => 'menunggu',
            'report_type' => $user->role === 'siswa' ? 'self' : 'teacher',
            'skor_prioritas' => 0,
        ]);

        $totalSkor = 0;
        $kriteriaLabels = [];

        foreach ($request->kriteria as $kriteriaId => $skor) {
            $subNama = $request->sub_kriteria[$kriteriaId] ?? '';

            $kriteria = Kriteria::find($kriteriaId);
            $bobot = $kriteria->bobot / 100 ?? 1;

            $totalSkor += $skor * $bobot;

            // Simpan label kriteria berdasarkan nama kriteria
            if ($kriteria->nama === 'Tingkat Urgensi') {
                $kriteriaLabels['tingkat_urgensi_label'] = $subNama;
                $kriteriaLabels['tingkat_urgensi_skor'] = $skor;
            } elseif ($kriteria->nama === 'Dampak Masalah') {
                $kriteriaLabels['dampak_masalah_label'] = $subNama;
                $kriteriaLabels['dampak_masalah_skor'] = $skor;
            } elseif ($kriteria->nama === 'Kategori Masalah') {
                $kriteriaLabels['kategori_masalah_label'] = $subNama;
                $kriteriaLabels['kategori_masalah_skor'] = $skor;
            } elseif ($kriteria->nama === 'Riwayat Konseling') {
                $kriteriaLabels['riwayat_konseling_label'] = $subNama;
                $kriteriaLabels['riwayat_konseling_skor'] = $skor;
            }

            PermohonanKriteria::create([
                'permohonan_konseling_id' => $permohonan->id,
                'kriteria_id' => $kriteriaId,
                'kriteria_nama' => $kriteria->nama,
                'sub_kriteria_nama' => $subNama,
                'skor' => $skor,
                'bobot' => $bobot,
            ]);
        }

        $permohonan->update(array_merge(['skor_prioritas' => $totalSkor], $kriteriaLabels));
        $guruBk = User::whereHas('guru', fn($q) => $q->where('role_guru', 'bk'))->get();
        foreach ($guruBk as $guru) {
            $guru->notify(new PermohonanKonselingNotification(
                $permohonan,
                "$user->name mengajukan permohonan konseling."
            ));
        }

        return redirect()->back()->with('success', 'Permohonan konseling berhasil diajukan.');
    }


    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'tanggal_disetujui' => 'required|date',
            'tempat' => 'required|string|max:255',
        ]);

        $permohonan = PermohonanKonseling::findOrFail($id);

        $permohonan->update([
            'tanggal_disetujui' => $request->tanggal_disetujui,
            'tempat' => $request->tempat,
        ]);

        $pesan = 'Jadwal konseling Anda telah diperbarui. '
            . 'Tanggal: ' . Carbon::parse($request->tanggal_disetujui)->format('d M Y')
            . ', Tempat: ' . $request->tempat;

        Notification::send(
            $permohonan->siswa->user,
            new PermohonanKonselingNotification($permohonan, $pesan)
        );

        if ($permohonan->siswa->orangtua && $permohonan->siswa->orangtua->user) {
            Notification::send(
                $permohonan->siswa->orangtua->user,
                new PermohonanKonselingNotification($permohonan, $pesan)
            );
        }

        return back()->with('success', 'Jadwal konseling berhasil diperbarui.');
    }

    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->back()->with('error', 'Hanya guru BK yang dapat menyetujui permohonan.');
        }

        $request->validate([
            'tanggal_disetujui' => 'required|date',
            'tempat' => 'required|string|max:255',
        ]);

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'disetujui',
            'tanggal_disetujui' => $request->tanggal_disetujui,
            'tempat' => $request->tempat,
            'nama_konselor' => Auth::user()->name,
        ]);

        if ($permohonan->report_type === 'teacher') {
            $waliKelas = Guru::where('role_guru', 'walikelas')
                ->where('kelas_id', $permohonan->siswa->kelas_id)
                ->first();

            if ($waliKelas && $waliKelas->user) {
                Notification::send(
                    $waliKelas->user,
                    new PermohonanKonselingNotification(
                        $permohonan,
                        'Permohonan konseling yang Anda ajukan telah disetujui oleh guru BK.'
                    )
                );
            }
        } else {
            Notification::send(
                $permohonan->siswa->user,
                new PermohonanKonselingNotification(
                    $permohonan,
                    'Permohonan konseling Anda telah disetujui.'
                )
            );
        }
        return redirect()->back()->with('success', 'Permohonan konseling berhasil disetujui.');
    }

    public function getRiwayatKonseling($siswaId)
    {
        $jumlahRiwayat = PermohonanKonseling::where('siswa_id', $siswaId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'selesai')
            ->count();

        $sub = SubKriteria::whereHas('kriteria', function ($q) {
            $q->where('nama', 'Riwayat Konseling');
        })
            ->where('range_min', '<=', $jumlahRiwayat)
            ->where('range_max', '>=', $jumlahRiwayat)
            ->first();

        if (!$sub) {
            return response()->json([
                'jumlah' => $jumlahRiwayat,
                'nama'   => 'Tidak Terdefinisi',
                'skor'   => 0
            ]);
        }

        return response()->json([
            'jumlah' => $jumlahRiwayat,
            'nama'   => $sub->nama_sub,
            'skor'   => $sub->skor,
            'range'  => $sub->range_min . ' - ' . $sub->range_max
        ]);
    }


    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->back()->with('error', 'Hanya guru BK yang dapat menolak permohonan.');
        }

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah ditolak.'));

        return redirect()->back()->with('success', 'Permohonan konseling berhasil ditolak.');
    }

    public function complete(Request $request, $id)
    {
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->back()->with('error', 'Hanya guru BK yang dapat menyelesaikan permohonan.');
        }

        $request->validate([
            'rangkuman' => 'required|string',
        ]);

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'selesai',
            'rangkuman' => $request->rangkuman,
            'nama_konselor' => Auth::user()->name,
        ]);

        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah selesai.'));

        return redirect()->back()->with('success', 'Permohonan konseling telah selesai.');
    }
}
