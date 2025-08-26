<?php

namespace App\Http\Controllers;

use App\Models\PermohonanKonseling;
use App\Models\KategoriKonseling;
use App\Models\Siswa;
use App\Notifications\PermohonanKonselingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Models\User;

class PermohonanKonselingController extends Controller
{
    public function index()
    {
        $permohonanKonseling = PermohonanKonseling::with(['siswa.user', 'kategori'])
        ->where('status', 'menunggu')
            ->orderBy('skor_prioritas', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        $kategoriKonseling = KategoriKonseling::all();
        return view('permohonan-konseling.index', compact('permohonanKonseling', 'kategoriKonseling'));
    }

    public function store(Request $request)
    {
        // Cek apakah user adalah siswa
        if (Auth::user()->role !== 'siswa') {
            return redirect()->back()->with('error', 'Hanya siswa yang dapat membuat permohonan konseling.');
        }

        $request->validate([
            'kategori_id' => 'required|exists:kategori_konseling,id',
            'tanggal_pengajuan' => 'required|date',
            'deskripsi_permasalahan' => 'required|string',
        ]);

        $kategori = KategoriKonseling::findOrFail($request->kategori_id);

        $permohonan = PermohonanKonseling::create([
            'siswa_id' => Auth::user()->id,
            'kategori_id' => $request->kategori_id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'deskripsi_permasalahan' => $request->deskripsi_permasalahan,
            'status' => 'menunggu',
            'skor_prioritas' => $kategori->skor_prioritas,
        ]);

        $guruBk = User::whereHas('guru', function($q) {
        $q->where('role_guru', 'bk');
    })->get();

    $user = Auth::user()->name;
    foreach ($guruBk as $guru) {
        $guru->notify(new PermohonanKonselingNotification($permohonan,  `$user mengajukan permohonan konseling.`  ));
    }

        return redirect()->back()->with('success', 'Permohonan konseling berhasil diajukan.');
    }

    public function approve(Request $request, $id)
    {
        // Cek apakah user adalah guru dengan role BK
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
        ]);

        // Kirim notifikasi ke siswa
        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah disetujui.'));

        return redirect()->back()->with('success', 'Permohonan konseling berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        // Cek apakah user adalah guru dengan role BK
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->back()->with('error', 'Hanya guru BK yang dapat menolak permohonan.');
        }

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'ditolak',
        ]);

        // Kirim notifikasi ke siswa
        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah ditolak.'));

        return redirect()->back()->with('success', 'Permohonan konseling berhasil ditolak.');
    }

    public function complete(Request $request, $id)
    {
        // Cek apakah user adalah guru dengan role BK
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
        ]);

        // Kirim notifikasi ke siswa
        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah selesai.'));

        return redirect()->back()->with('success', 'Permohonan konseling telah selesai.');
    }
}
